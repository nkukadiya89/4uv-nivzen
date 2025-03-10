<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\DistributorRegister;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class DistributorController extends Controller
{
    public function index() {
        $title = 'Manage Distributors';
        return view('admin.distributors.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('enagic_id','firstname','lastname', 'email', 'phone','dob', 'city', 'state', 'country', 'status');
        //$query = new User();

        // Add condition to fetch only 'Distributor' type users
        //$query = $query->where('type', 'Distributor');
//        if (auth()->user()->hasRole('Administrator')) {
//            $query = User::role('Distributor');
//        } else {
//            $query = User::role('Distributor')->where('upline_id', auth()->id());
//        }
        //$query = User::role('Distributor')->where('upline_id', auth()->id());

        $query = User::query()
            ->select([
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.email',
                'users.phone',
                'users.dob',
                'users.city',
                'users.state',
                'users.country',
                'users.enagic_id',
                'users.status',
                DB::raw('COUNT(DISTINCT prospects.id) as total_prospects'), // Count total prospects created by the user
                DB::raw('COUNT(CASE WHEN prospect_statuses.status = "Invitation" THEN 1 END) as invitation_count'),
                DB::raw('COUNT(CASE WHEN prospect_statuses.status = "Demo" THEN 1 END) as demo_count'),
                DB::raw('COUNT(CASE WHEN prospect_statuses.status = "Followup" THEN 1 END) as followup_count'),
                DB::raw('COUNT(CASE WHEN prospect_statuses.status = "Machine Purchased" THEN 1 END) as machine_purchased_count')
            ])
            ->leftJoin('prospect_statuses', 'users.id', '=', 'prospect_statuses.prospect_id') // Ensure proper join
            ->leftJoin('prospects', 'users.id', '=', 'prospects.created_by') // Join with prospects to count total prospects
            ->where('users.type', 'Distributor')
            ->when(!auth()->user()->hasRole('Administrator'), function ($query) {
                $query->where('users.upline_id', auth()->id()); // Apply condition if not Administrator
            })
            ->where('users.id', '!=', auth()->id()) // Exclude the authenticated user
            ->groupBy('users.id');
        if (isset($data['enagic_id']) && $data['enagic_id'] != '') {
            $query = $query->where('enagic_id', 'LIKE', '%' . $data['enagic_id'] . '%');
        }

        if (isset($data['firstname']) && $data['firstname'] != '') {
            $query = $query->where('firstname', 'LIKE', '%' . $data['firstname'] . '%');
        }

        if (isset($data['lastname']) && $data['lastname'] != '') {
            $query = $query->where('lastname', 'LIKE', '%' . $data['lastname'] . '%');
        }

        if (isset($data['email']) && $data['email'] != '') {
            $query = $query->where('email', 'LIKE', '%' . $data['email'] . '%');
        }

        if (isset($data['phone']) && $data['phone'] != '') {
            $query = $query->where('phone', 'LIKE', '%' . $data['phone'] . '%');
        }

        if (isset($data['date_range']) && $data['date_range'] != '') {
            $date_range = explode('→', trim($data['date_range']));
            $query = $query->where(DB::raw('DATE(users.dob)'), '>=', trim(date('Y-m-d',strtotime(trim($date_range[0])))));
            $query = $query->where(DB::raw('DATE(users.dob)'), '<=', trim(date('Y-m-d',strtotime(trim($date_range[1])))));
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
        if (isset($data['status']) && $data['status'] != '') {
            $query = $query->where('status', '=', $data['status']);
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

            //$data[$key][$index++] = $val['id'];
            $data[$key][$index++] = '<input type="checkbox" class="row-checkbox" value="' . $val['id'] . '">';
            $data[$key][$index++] = $val['enagic_id'];
            $data[$key][$index++] = $val['firstname'];
            $data[$key][$index++] = $val['lastname'];
            $data[$key][$index++] = $val['email'];
            $data[$key][$index++] = $val['phone'];
            $data[$key][$index++] = date('d F, Y', strtotime($val['dob']));
            $data[$key][$index++] = $val['city'];
            $data[$key][$index++] = $val['state'];
            $data[$key][$index++] = $val['country'];
            if (auth()->user()->hasRole('Administrator')) {
                $data[$key][$index++] = '<a rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'prospects/filter/' . $val['id'] . '" class="prospect-link" data-user-id="' .  $val['id'] . '">' . $val['total_prospects'] . '</a>';
            } else {
                $data[$key][$index++] = $val['total_prospects']; // Just show the number if not an Administrator
            }

            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('distributor edit')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'distributors/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('distributor view')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'distributors/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('distributor delete')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . config('constant.ADMIN_URL') . 'distributors/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

    public function showDistributorForm () {
        $title = 'Add Distributor';
        $roles = Role::pluck('name','name')->all();
        //$users = User::where('id', '!=', auth()->id())->select('id', 'firstname', 'lastname')->get();
        $upLineUsers = User::role('Distributor')
            //->where('upline_id', auth()->id())
            ->where('id', '!=', auth()->id())
            ->select('id', 'firstname', 'lastname')
            ->get();
//        $user = auth()->user();
//        $users = $user->downlines;
        $superUsers = User::whereDoesntHave('roles', function ($query) {
                       $query->where('name', 'Distributor');
                  })
            //->where('upline_id', auth()->id())
            ->where('id', '!=', auth()->id())
            ->select('id', 'firstname', 'lastname')
            ->get();
        return view('admin.distributors.add', compact('title', 'upLineUsers', 'superUsers','roles'));
    }

    public function addDistributor(Request $request) {
        $inputs = $request->all();
        $randomPassword = Str::random(10);
        $hashedPassword = Hash::make($randomPassword);
        $validator = Validator::make($inputs, [
            'enagic_id' => 'required',
            'phone' => 'required|digits:10|numeric',
            'email' => 'required|email|unique:users,email',
            'dob' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'type' => 'required',
            'distributor_status' => 'required',
            'goal_for' => 'required',
            'upline_id' => 'required',
            'leader_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        } else {

            $distributor = new User();
            $distributor->enagic_id= $request->enagic_id;
            $distributor->phone= $request->phone;
            $distributor->email= $request->email;
            $distributor->dob =  $request->dob;
            $distributor->password= $hashedPassword;
            $distributor->firstname= $request->firstname;
            $distributor->lastname= $request->lastname;
            $distributor->address1= $request->address1;
            $distributor->address2= $request->address2;
            $distributor->city= $request->city;
            $distributor->state= $request->state;
            $distributor->country= $request->country;
            $distributor->type = $request->type;
            $distributor->distributor_status = $request->distributor_status;
            $distributor->upline_id = $request->upline_id;
            $distributor->leader_id = $request->leader_id;
            $distributor->status = isset($request->status) && $request->status == 'Active' ? 1: 0;
            $distributor->feature_access = '1';

            // Set Role Based on User's Role
            if (auth()->user()->hasRole('Administrator')) {
                $role = $request->roles ?? 'Distributor'; // If no role selected, default to 'Distributor'
            } else {
                $role = 'Distributor'; // Non-Admins can only assign 'Distributor'
            }

            $distributor->save();
            $distributor->syncRoles($role);

            if ($distributor->save()) {
                Mail::to($distributor->email)->send(new DistributorRegister($distributor, $randomPassword));
                Session::flash('success-message', 'Distributor'. $request->firstname . " created successfully !");
                $data['success'] = true;

                return response()->json($data);
            }
            return redirect()->back()->with("success", " Distributor added successfully !");
        }
    }

    public function editDistributor(Request $request, $id)
    {

        $distributor = User::find($id);
        $title = 'Edit Distributor';
        $roles = Role::pluck('name','name')->all();
        $userRoles = $distributor->roles->pluck('name','name')->all();
        $inputs  = $request->all();
        if ($distributor || !empty($distributor)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'enagic_id' => 'required',
                    'phone' => 'required|digits:10|numeric',
                    'email' => 'required|unique:users,email,"'.$id.'"',
                    'dob' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'address1' => 'required',
                    'address2' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'type' => 'required',
                    'distributor_status' => 'required',
                    'goal_for' => 'required',
                    'upline_id' => 'required',
                    'leader_id' => 'required',
                    'status' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    $distributor->enagic_id= $request->enagic_id;
                    $distributor->phone= $request->phone;
                    $distributor->email= $request->email;
                    $distributor->dob =  $request->dob;
                    $distributor->firstname= $request->firstname;
                    $distributor->lastname= $request->lastname;
                    $distributor->address1= $request->address1;
                    $distributor->address2= $request->address2;
                    $distributor->city= $request->city;
                    $distributor->state= $request->state;
                    $distributor->country= $request->country;
                    $distributor->type = $request->type;
                    $distributor->distributor_status = $request->distributor_status;
                    $distributor->upline_id = $request->upline_id;
                    $distributor->leader_id = $request->leader_id;
                    $distributor->status = isset($request->status) && $request->status == 'Active' ? 1: 0;

                    if (auth()->user()->hasRole('Administrator')) {
                        $role = $request->roles ?? 'Distributor'; // If no role selected, default to 'Distributor'
                    } else {
                        $role = 'Distributor'; // Non-Admins can only assign 'Distributor'
                    }

                    $distributor->save();
                    $distributor->syncRoles($role);

                    if ($distributor->save()) {
                        Session::flash('success-message', $distributor->firstname . " updated successfully !");
                        $data['redirect_url'] = config('constants.ADMIN_URL') . 'distributors';
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Distributor updated successfully !");
                }
            } else {
                //$users = User::where('id', '!=', auth()->id())->select('id', 'firstname', 'lastname')->get();
//                $users = User::role('Distributor')
//                    ->where('upline_id', auth()->id())
//                    ->where('id', '!=', auth()->id())
//                    ->select('id', 'firstname', 'lastname')
//                    ->get();
//                $user = auth()->user();
//                $users = $user->downlines;
                $upLineUsers = User::role('Distributor')
                    //->where('upline_id', auth()->id())
                    ->where('id', '!=', auth()->id())
                    ->select('id', 'firstname', 'lastname')
                    ->get();
//        $user = auth()->user();
//        $users = $user->downlines;
                $superUsers = User::whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'Distributor');
                })
                    //->where('upline_id', auth()->id())
                    ->where('id', '!=', auth()->id())
                    ->select('id', 'firstname', 'lastname')
                    ->get();

                return view('admin.distributors.edit', compact('title', 'distributor', 'upLineUsers', 'superUsers','userRoles','roles'));
                //return view('admin.distributors.edit', compact('distributor', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'distributors');
    }

    public function viewDistributor($id) {

        $distributor = User::find($id);
        $title = 'View Distributor ';
        return view('admin.distributors.view', compact('title', 'distributor'));
    }
    public function deleteDistributor($id)
    {
        $object = User::find($id);

        if ($object) {
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
            // Perform delete operation
            User::whereIn('id', $ids)->delete();
            return response('TRUE');
            //return response()->json(['message' => 'Records deleted successfully.']);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }
}
