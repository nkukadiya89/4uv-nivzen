<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function __construct()
    {
        // $this->middleware('permission:view role', ['only' => ['index']]);
        // $this->middleware('permission:create role', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        // $this->middleware('permission:update role', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::get();
        $title = 'Role';
        return view('admin.role-permission.role.index', compact('roles', 'title'));
    }

    public function create()
    {
        $title = 'Create role';
        return view('admin.role-permission.role.create', compact('title'));
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'role_name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {
            Role::create([
                'name' => $request->role_name,
                'guard_name' => 'backend'
            ]);
        }

        Session::flash('success-message', $request->role_name . "role created successfully !");

        $data['success'] = true;

        return response()->json($data);  
    }

    public function edit(Role $role)
    {
        $title = 'Edit role';

        return view('admin.role-permission.role.edit',[
            'role' => $role,
            'title' => $title
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'role_name' => [
                'required',
                'string',
                'unique:roles,name,'.$role->id
            ]
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());
        } else {
            $role->update([
                'name' => $request->role_name
            ]);
        }

        Session::flash('success-message', $request->role_name . " role updated successfully !");

        $data['success'] = true;

        return response()->json($data);     
    }

    public function destroy($roleId)
    {
        $role = Role::find($roleId);
        $role->delete();
        return redirect('backend/roles')->with('status','Role Deleted Successfully');
    }

    public function addPermissionToRole($roleId)
    {
        // $permissions = DB::table('permissions')->groupBy('model_name')->all();

        // $permissions = DB::table('permissions')
        //     ->select('permissions.id', 'permissions.name','permissions.model_name')
        //     ->groupBy('permissions.model_name')
        //     ->get();

        $permissions = Permission::select('id','model_name', 'name')
        ->get()
        ->groupBy('model_name');

        
        $role = Role::findOrFail($roleId);

        $rolePermissions =  DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

                                
        
        return view('admin.role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'title' => ucfirst($role->name). ' Role Permissions'
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'permission' => 'required'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());
        } else {
            $role = Role::findOrFail($roleId);
        
            $role->syncPermissions($request->permission);

        }
        Session::flash('success-message', $role->name . " Permissions added to role !");

        $data['success'] = true;

        
        return response()->json($data); 
    }
}
