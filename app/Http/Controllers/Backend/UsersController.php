<?php

namespace App\Http\Controllers\Backend;

use App\Country;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ActivateUser;
use App\Models\Batch;
use App\Mail\UserRegister;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{

//    public function __construct()
//    {
//        // $this->middleware('role:view user', ['only' => ['index']]);
//        // $this->middleware('role:create user', ['only' => ['create','store']]);
//        // $this->middleware('role:update user', ['only' => ['update','edit']]);
//        // $this->middleware('role:delete user', ['only' => ['destroy']]);
//    }

    public function index()
    {
        $title = 'Manage Users';
        return view('admin.user.manage', compact( 'title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('firstname','lastname','email','phone','dob', 'city', 'state', 'country', 'status');
       
        $authUser = Auth::user();
        $query = User::manageableBy($authUser)->with('roles');

        $query = $query->where('id', '!=', auth()->id());
        $query = $query->where('type', 'User');

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
        ;
        $arrUsers = $users->toArray();
        $data = array();
       
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;

            //$data[$key][$index++] = $val['id'];
            $data[$key][$index++] = '<input type="checkbox" class="row-checkbox" value="' . $val['id'] . '">';
            $data[$key][$index++] = $val['firstname'];
            $data[$key][$index++] = $val['lastname'];
            $data[$key][$index++] = $val['email'];
            $data[$key][$index++] = $val['phone'];
            $data[$key][$index++] = date('d F, Y', strtotime($val['dob']));
            $data[$key][$index++] = $val['city'];
            $data[$key][$index++] = $val['state'];
            $data[$key][$index++] = $val['country'];

            // Fetch user and their roles
            $user = User::find($val['id']);
            $userRoles = $user->roles->pluck('name')->implode(', '); // Get roles as a comma-separated string
            $data[$key][$index++] = $userRoles; // Add roles to the data table

            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';

            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('update user')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'users/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('view user')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'users/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('delete user')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . config('constant.ADMIN_URL') . 'users/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

    public function showUsersForm () {
        $title = 'Add User';
        $roles = Role::pluck('name','name')->all();
        return view('admin.user.add', compact('title','roles'));
    }

    public function addUSer(Request $request) {
        $inputs = $request->all();
        $randomPassword = Str::random(10);
        $hashedPassword = Hash::make($randomPassword);
        $validator = Validator::make($inputs, [
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
            'pincode' => 'digits:6|numeric',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            //return json_encode($validator->errors());
            return response()->json(['errors' => $validator->errors()], 422);

        } else {

            $user = new User();
            $user->phone= $request->phone;
            $user->email= $request->email;
            $user->dob =  $request->dob;
            $user->password= $hashedPassword;
            $user->name= $request->firstname;
            $user->firstname= $request->firstname;
            $user->lastname= $request->lastname;
            $user->address1= $request->address1;
            $user->address2= $request->address2;
            $user->city= $request->city;
            $user->state= $request->state;
            $user->country= $request->country;
            $user->pincode= $request->pincode;
            $user->status =isset($request->status) && $request->status == 1 ? 1: 0;
            $user->feature_access = '1';
            $user->upline_id = $request->upline_id ?? null;
            $user->leader_id = $request->leader_id ?? null;
            $user->enagic_id = $request->enagic_id ?? null;
            $user->type = $request->type ?? 'User';
            $user->distributor_status = $request->distributor_status ?? 'Inactive';
            $user->goal_for = $request->goal_for ?? 'User';

            $user->save();
            $user->syncRoles($request->roles);
            if ($user->save()) {
                Mail::to($user->email)->send(new UserRegister($user, $randomPassword));
                Session::flash('success-message', $request->firstname . " created successfully !");
                $data['success'] = true;

                return response()->json($data);
            }
            return redirect()->back()->with("success", " User added successfully !");
        }
    }

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        $title = 'Edit Users';
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        $inputs  = $request->all();



        if ($user || !empty($user)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|unique:users,email,"'.$id.'"',
                    'dob' => 'required',
                    'phone' => 'required|digits:10|numeric',
                    'roles' => 'required',
                    'pincode' => 'digits:6|numeric',
                ]);

                if ($validator->fails()) {
                    //return json_encode($validator->errors());
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    $user->name = $request->firstname.' '.$request->lastname;
                    $user->firstname = $request->firstname;
                    $user->lastname =  $request->lastname;
                    $user->email =  $request->email;
                    $user->dob =  $request->dob;
                    $user->phone =  $request->phone;
                    $user->feature_access = '1';
                    $user->city = $request->city;
                    $user->state = $request->state;
                    $user->country = $request->country;
                    $user->address1 = $request->address1;
                    $user->address2 = $request->address2;
                    $user->pincode = $request->pincode;
                    $user->status =isset($request->status) && $request->status == 1 ? 1: 0;

                    $user->save();
                    $user->syncRoles($request->roles);

                    if ($user->save()) {
                        Session::flash('success-message', $user->name . " updated successfully !");
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " User updated successfully !");
                }
            } else {
                return view('admin.user.edit', compact('user', 'title', 'userRoles','roles'));

            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'users');
    }

   
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        $userRoles = $user->roles->pluck('name','name')->all();
        $title = 'View User';
        return view('admin.user.user_details', compact('user', 'title', 'userRoles'));
    }

    public function deleteUser($id)
    {
        $catCss = User::find($id);

        if ($catCss) {
            if ($catCss->delete()) {
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
