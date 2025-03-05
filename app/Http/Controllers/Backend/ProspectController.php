<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\DistributorRegister;
use App\Models\Prospect;
use App\Models\ProspectStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ProspectController extends Controller
{
    public function index() {
        $title = 'Manage Prospects';
        return view('admin.prospects.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('name','email','mobile_no', 'city', 'state', 'country');
        $query = new Prospect();

        $query = Prospect::withCount([
            'statuses as invitation_count' => function ($query) {
                $query->where('status', 'Invitation');
            },
            'statuses as demo_count' => function ($query) {
                $query->where('status', 'Demo');
            },
            'statuses as follow_up_count' => function ($query) {
                $query->where('status', 'Followup');
            }
        ]);

        // Check if the user is not an Administrator, filter by created_by
        if (!auth()->user()->hasRole('Administrator')) {
            $query = $query->where('created_by', auth()->id());
            $query = $query->where('status', 0);
        }

        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        if (isset($data['email']) && $data['email'] != '') {
            $query = $query->where('email', 'LIKE', '%' . $data['email'] . '%');
        }

        if (isset($data['mobile_no']) && $data['mobile_no'] != '') {
            $query = $query->where('mobile_no', 'LIKE', '%' . $data['mobile_no'] . '%');
        }

        if (isset($data['city']) && $data['city'] != '') {
            $query = $query->where('city', 'LIKE', '%' . $data['city'] . '%');
        }

        if (isset($data['state']) && $data['state'] != '') {
            $query = $query->where('state', 'LIKE', '%' . $data['state'] . '%');
        }

        if (isset($data['country']) && $data['country'] != '') {
            $query = $query->where('country', 'LIKE', '%' . $data['country'] . '%');
        }

        $rec_per_page = 10;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        if ($sort_order != '' && $order_field != '') {
            $query = $query->orderBy($order_field, $sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $users = $query->paginate($rec_per_page);

        $arrUsers = $users->toArray();
        $data = array();
        $style1 = 'border:2px; width:50px;height: 13px;display: inline-block;';//background-color:#28C8FB;';

        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            $data[$key][$index++] = '<input type="checkbox" class="row-checkbox" value="' . $val['id'] . '">';
            //$data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['name'];
            $data[$key][$index++] = $val['email'];
            $data[$key][$index++] = $val['mobile_no'];
            $data[$key][$index++] = $val['city'];
            $data[$key][$index++] = $val['state'];
            $data[$key][$index++] = $val['country'];
// Add demo and follow-up counts
            $data[$key][$index++] = $val['invitation_count'];
            $data[$key][$index++] = $val['demo_count'];
            $data[$key][$index++] = $val['follow_up_count'];
            //$data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects edit')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'prospects/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects view')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'prospects/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects delete')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . config('constant.ADMIN_URL') . 'prospects/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function filterManage(Request $request, $id){
        $title = 'Filter Prospects';
        $user = User::find($id);
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('admin.prospects.filter', compact('title', 'user'));
    }

    public function filterListAjax(Request $request)
    {
        $id = $request->query('id'); // Get 'id' from the query string
        $data = $request->all();

        $sortColumn = array('name','email','mobile_no', 'city', 'state', 'country');
        $query = new Prospect();

        $query = Prospect::withCount([
            'statuses as invitation_count' => function ($query) {
                $query->where('status', 'Invitation');
            },
            'statuses as demo_count' => function ($query) {
                $query->where('status', 'Demo');
            },
            'statuses as follow_up_count' => function ($query) {
                $query->where('status', 'Followup');
            }
        ]);

        // Check if the user is not an Administrator, filter by created_by
        if (!auth()->user()->hasRole('Administrator')) {
            $query = $query->where('created_by', auth()->id());
            $query = $query->where('status', 0);
        }
        if ($id) {
            $query->where('created_by', $id);
        }

        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        if (isset($data['email']) && $data['email'] != '') {
            $query = $query->where('email', 'LIKE', '%' . $data['email'] . '%');
        }

        if (isset($data['mobile_no']) && $data['mobile_no'] != '') {
            $query = $query->where('mobile_no', 'LIKE', '%' . $data['mobile_no'] . '%');
        }

        if (isset($data['city']) && $data['city'] != '') {
            $query = $query->where('city', 'LIKE', '%' . $data['city'] . '%');
        }

        if (isset($data['state']) && $data['state'] != '') {
            $query = $query->where('state', 'LIKE', '%' . $data['state'] . '%');
        }

        if (isset($data['country']) && $data['country'] != '') {
            $query = $query->where('country', 'LIKE', '%' . $data['country'] . '%');
        }

        $rec_per_page = 10;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        if ($sort_order != '' && $order_field != '') {
            $query = $query->orderBy($order_field, $sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $users = $query->paginate($rec_per_page);

        $arrUsers = $users->toArray();
        $data = array();
        $style1 = 'border:2px; width:50px;height: 13px;display: inline-block;';//background-color:#28C8FB;';

        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            $data[$key][$index++] = '<input type="checkbox" class="row-checkbox" value="' . $val['id'] . '">';
            //$data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['name'];
            $data[$key][$index++] = $val['email'];
            $data[$key][$index++] = $val['mobile_no'];
            $data[$key][$index++] = $val['city'];
            $data[$key][$index++] = $val['state'];
            $data[$key][$index++] = $val['country'];
// Add demo and follow-up counts
            $data[$key][$index++] = $val['invitation_count'];
            $data[$key][$index++] = $val['demo_count'];
            $data[$key][$index++] = $val['follow_up_count'];
            //$data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects edit')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '"  href="' . route('prospect-edit', ['id' => $val['id']]) . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects view')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '"  href="' . route('prospect-view', ['id' => $val['id']]) . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects delete')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . route('prospect-delete', ['id' => $val['id']]) . '"><i class="la la-trash"></i></a>';
            }

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;

    }

    public function showProspectForm () {
        $title = 'Add Prospect';
        return view('admin.prospects.add', compact('title'));
    }

    public function addProspect(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
            'email' => 'required|email|unique:prospects,email',
            'mobile_no' => 'required|digits:10|numeric',
            'address' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'statuses' => 'required|array', // Ensure statuses is an array
            'statuses.*.status' => 'required|in:Prospect,Invitation,Demo,Followup,Machine Purchased',
            'statuses.*.date' => 'required|date',
            'statuses.*.remarks' => 'nullable|string'
        ]);

        // Check if "Machine Purchased" appears more than once
        $machinePurchasedCount = collect($request->statuses)->where('status', 'Machine Purchased')->count();

        if ($machinePurchasedCount > 1) {
            return response()->json([
                'errors' => "Machine Purchased entry can only be added once."
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $prospect = new Prospect();
            $prospect->name= $request->name;
            $prospect->email= $request->email;
            $prospect->mobile_no= $request->mobile_no;
            $prospect->address= $request->address;
            $prospect->area= $request->area;
            $prospect->city= $request->city;
            $prospect->state= $request->state;
            $prospect->country= $request->country;
            $prospect->created_by = auth()->id();
            $prospect->save();

            if ($prospect->save()) {
                // Add prospect statuses
                // Add prospect statuses with created_by
                $statuses = collect($request->statuses)->map(function ($status) {
                    return array_merge($status, ['created_by' => auth()->id()]);
                })->toArray();

                $prospect->statuses()->createMany($statuses);
                // **If "Machine Purchased" is selected, convert to Distributor**
                if ($machinePurchasedCount == 1) {
                    $upLineUsers = User::role('Distributor')
                        //->where('upline_id', auth()->id())
                        ->where('id', '!=', auth()->id())
                        ->select('id', 'firstname', 'lastname')
                        ->get();
                    $superUsers = User::whereDoesntHave('roles', function ($query) {
                        $query->where('name', 'Distributor');
                    })
                        //->where('upline_id', auth()->id())
                        ->where('id', '!=', auth()->id())
                        ->select('id', 'firstname', 'lastname')
                        ->get();

                    $data['showDistributorModal'] = true;
                    $data['prospect_id'] = $prospect->id;
                    $data['name'] = $prospect->name;
                    $data['email'] = $prospect->email;
                    $data['upLineUsers'] = $upLineUsers;
                    $data['superUsers'] = $superUsers;
                    return response()->json($data);
                } else {
                    Session::flash('success-message', $request->name . " created successfully !");
                    $data['success'] = true;

                    return response()->json($data);
                }
            }
            return redirect()->back()->with("success", " Prospect added successfully !");
        }
    }

    public function editProspect(Request $request, $id)
    {

        $prospect = Prospect::with(['statuses' => function($query) {
            $query->orderBy('id'); // Order statuses by id
        }])->find($id);
        $title = 'Edit Prospect';
        $inputs  = $request->all();
        if ($prospect || !empty($prospect)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'name' => 'required',
                    'email' => 'required|unique:prospects,email,"'.$id.'"',
                    'mobile_no' => 'required|digits:10|numeric',
                    'address' => 'required',
                    'area' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'statuses' => 'array', // Ensure statuses array exists
                    'statuses.*.status' => 'required|string',
                    'statuses.*.date' => 'nullable|date',
                    'statuses.*.remarks' => 'nullable|string',
                ]);

                // **Check if "Machine Purchased" appears more than once**
                $machinePurchasedCount = collect($request->statuses)->where('status', 'Machine Purchased')->count();

                if ($machinePurchasedCount > 1) {
                    return response()->json([
                        'errors' => "Machine Purchased entry can only be added once."
                    ], 422);
                }

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    // Update prospect details
                    $prospect->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile_no' => $request->mobile_no,
                        'address' => $request->address,
                        'area' => $request->area,
                        'city' => $request->city,
                        'state' => $request->state,
                        'country' => $request->country,
                        'updated_by' => auth()->id()
                    ]);


                    if ($request->has('statuses')) {
                        $existingStatusIds = $prospect->statuses->pluck('id')->toArray(); // Get existing status IDs

                        foreach ($request->statuses as $status) {
                            if (!empty($status['id']) && in_array($status['id'], $existingStatusIds)) {
                                // Update existing status
                                $prospect->statuses()->where('id', $status['id'])->update([
                                    'status' => $status['status'],
                                    'date' => $status['date'],
                                    'remarks' => $status['remarks'],
                                    'updated_by' => auth()->id()
                                ]);
                            } else {
                                // Insert new status if it doesn't exist
                                $prospect->statuses()->create([
                                    'status' => $status['status'],
                                    'date' => $status['date'],
                                    'remarks' => $status['remarks'],
                                    'created_by' => auth()->id()
                                ]);
                            }
                        }

                        // Remove statuses that were removed from the form
                        $requestStatusIds = collect($request->statuses)->pluck('id')->filter()->toArray();
                        //$prospect->statuses()->whereNotIn('id', $requestStatusIds)->delete();
                    }

                    if ($prospect->save()) {
                        // **If "Machine Purchased" is selected, convert to Distributor**
                        if ($machinePurchasedCount == 1) {
                            $upLineUsers = User::role('Distributor')
                                ->where('id', '!=', auth()->id())
                                ->select('id', 'firstname', 'lastname')
                                ->get();

                            $superUsers = User::whereDoesntHave('roles', function ($query) {
                                $query->where('name', 'Distributor');
                            })
                                ->where('id', '!=', auth()->id())
                                ->select('id', 'firstname', 'lastname')
                                ->get();

                            $data['showDistributorModal'] = true;
                            $data['prospect_id'] = $prospect->id;
                            $data['name'] = $prospect->name;
                            $data['email'] = $prospect->email;
                            $data['upLineUsers'] = $upLineUsers;
                            $data['superUsers'] = $superUsers;

                            return response()->json($data);
                        } else {
                            Session::flash('success-message', $prospect->name . " updated successfully !");
                            $data['redirect_url'] = config('constants.ADMIN_URL') . 'prospects';
                            $data['success'] = true;
                            return response()->json($data);
                        }
                    }
                    return redirect()->back()->with("success", " Prospect updated successfully !");
                }
            } else {
                return view('admin.prospects.edit', compact('title', 'prospect'));
                //return view('admin.distributors.edit', compact('distributor', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'prospects');
    }

    public function convertToDistributor(Request $request) {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'upline_id' => 'required|exists:users,id',
            'leader_id' => 'required|exists:users,id'
        ]);

        $prospect = Prospect::find($request->prospect_id);

        // Check if user already exists
        $user = User::where('email', $prospect->email)->first();

        if (!$user) {
            $randomPassword = Str::random(10);
            $hashedPassword = Hash::make($randomPassword);
            // Create new user with distributor role
            $user = new User();
            $user->firstname = $prospect->name;
            $user->lastname = $prospect->name;
            $user->email = $prospect->email;
            $user->phone = $prospect->mobile_no;
            $user->dob =  '1990-01-01';
            $user->address1 = $prospect->address;
            $user->city = $prospect->city;
            $user->state = $prospect->state;
            $user->country = $prospect->country;
            $user->upline_id = $request->upline_id;
            $user->leader_id = $request->leader_id;
            $user->type = $request->type;
            $user->enagic_id = $request->enagic_id;
            $user->distributor_status = 'Inactive';
            $user->password = $hashedPassword; // Set default password
            $user->feature_access = '1';
            $user->save();
            if ($request->type === 'Distributor') {
                $user->syncRoles('Distributor');
            }

            if ($user->save()) {
                $prospect->status = 1; // or true
                $prospect->save(); // Explicitly save
                Mail::to($user->email)->send(new DistributorRegister($user, $randomPassword));
                Session::flash('success-message', $prospect->name . " created Distributor successfully !");
                $data['convertSuccess'] = true;
                //$data['success'] = true;

                return response()->json($data);
            }
        } else {
            // Update existing user to Distributor
            $user->type = $request->type;
            $user->upline_id = $request->upline_id;
            $user->leader_id = $request->leader_id;
            $user->enagic_id = $request->enagic_id;
            $user->distributor_status = 'Inactive';
            $user->save();
            if ($request->type === 'Distributor') {
                $user->syncRoles('Distributor');
            }

            if ($user->save()) {
                //Mail::to($user->email)->send(new DistributorRegister($user, $randomPassword));
                Session::flash('success-message', $prospect->name . " created Distributor successfully !");
                $data['convertSuccess'] = true;
                //$data['success'] = true;

                return response()->json($data);
            }
        }

        return response()->json(['success' => true]);
    }
    public function viewProspect($id) {

        $prospect = Prospect::with(['statuses' => function($query) {
            $query->orderBy('id'); // Order statuses by id
        }])->find($id);
        $title = 'View Prospect ';
        return view('admin.prospects.view', compact('title', 'prospect'));
    }

    public function deleteProspect($id)
    {
        $object = Prospect::find($id);

        if ($object) {
            $object->deleted_by = auth()->id();
            if ($object->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action'); // Extract the 'action' value
        $ids = $request->input('ids') ?? []; // Extract the 'ids' array

        if (!$action) {
            return response()->json(['message' => 'No action selected.'], 400);
        }

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'No records selected.'], 400);
        }

        if ($action === 'Delete') {
            $userId = auth()->id();
            // Soft delete related ProspectStatus records first
            ProspectStatus::whereIn('prospect_id', $ids)->update(['deleted_by' => $userId]);
            ProspectStatus::whereIn('prospect_id', $ids)->delete(); // Soft delete related statuses

            // Soft delete Prospects
            Prospect::whereIn('id', $ids)->update(['deleted_by' => $userId]);
            Prospect::whereIn('id', $ids)->delete(); // Soft delete prospects
            return response('TRUE');
            //return response()->json(['message' => 'Records deleted successfully.']);
        }

        return response( 'Invalid action');
    }
}
