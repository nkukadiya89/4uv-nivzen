<?php

namespace App\Http\Controllers\Backend;

use App\Country;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ActivateUser;
use App\Models\Batch;
use App\Models\Batches;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{

    public function __construct()
    {
        // $this->middleware('role:view user', ['only' => ['index']]);
        // $this->middleware('role:create user', ['only' => ['create','store']]);
        // $this->middleware('role:update user', ['only' => ['update','edit']]);
        // $this->middleware('role:delete user', ['only' => ['destroy']]);
    }

    public function index()
    {
        $userId = Auth::id();
        $title = 'Manage Users';
        return view('admin.user.manage', compact( 'title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','name','email','phone','dob');
       
        $authUser = Auth::user();
        $query = User::manageableBy($authUser);

        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
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

            $data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['name'];
            $data[$key][$index++] = $val['email'];
            $data[$key][$index++] = $val['phone'];
            $data[$key][$index++] = date('d F, Y', strtotime($val['dob'])); ;

            $action = '';
            $action .= '<div class="d-flex">';

            
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'users/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';


            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'users/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'users/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
   
    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        $title = 'Edit Users';
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        $inputs  = $request->all();

        $selected_batches = $user->batches->pluck('id')->toArray();
        
        $batches = Batch::pluck('title','id');

        if ($user || !empty($user)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|unique:users,email,"'.$id.'"',
                    'dob' => 'required',
                    'phone' => 'required',
                    'roles' => 'required'
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
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
                    $user->batches()->detach(); 
                    $user->batches()->attach($request->user_batch); 
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
                return view('admin.user.edit', compact('user', 'title', 'batches', 'selected_batches','userRoles','roles'));

            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'users');
    }

   
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        $title = 'View Users';
        return view('admin.user.user_details', compact('user', 'title'));
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
}
