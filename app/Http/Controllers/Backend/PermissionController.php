<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create','store']]);
        $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('admin.role-permission.permission.index', [
            'title' => 'Permission'
        ]);
    }

    public function anyListAjax(Request $request) {
       
        $data = $request->all();

        $sortColumn = array('id','permission_name');
        $query = new Permission();

        if (isset($data['permission_name']) && $data['permission_name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['permission_name'] . '%');
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
        $style1 = 'border:2px; width:50px;height: 13px;display: inline-block;';//background-color:#28C8FB;';

        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;

            $data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['name'];
           
       
            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' .  url('backend/permissions/'.$val['id'].'/edit'). '" title="view"><i class="la la-edit"></i> </a>';

       
            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .   url('backend/permissions/'.$val['id'].'/delete') . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function create()
    {
        $title = 'Create permission';
        return view('admin.role-permission.permission.create',
            [
                'title' =>$title
            ]
        );
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'permission_name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {
            Permission::create([
                'name' => $request->permission_name,
                'guard_name' => 'backend'
            ]);
        }

        Session::flash('success-message', $request->permission_name . " created successfully !");

        $data['success'] = true;

        return response()->json($data);
        // return redirect('backend/permissions')->with('status','Permission Created Successfully');
    }

    public function edit(Permission $permission)
    {
        return view('admin.role-permission.permission.edit', ['permission' => $permission,'title'=> 'Edit Permission']);
    }

    public function update(Request $request, Permission $permission)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'permission_name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id
            ]
        ]);
        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {
            $permission->update([
                'name' => $request->permission_name,
                'guard_name' => 'backend'
            ]);
        }

        Session::flash('success-message', "Permission Updated Successfully");

        $data['success'] = true;

        return response()->json($data);

    }

    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();
        Session::flash('success-message'," Permission Deleted Successfully !");

        return redirect('backend/permissions');
    }
}
