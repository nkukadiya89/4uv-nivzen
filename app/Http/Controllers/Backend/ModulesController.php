<?php

namespace App\Http\Controllers\Backend;

use App\Country;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ActivateUser;
use App\Models\Course;
use App\Models\Modules;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ModulesController extends Controller
{

    public function index()
    {
        $title = 'Manage Modules';
        return view('admin.modules.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','module_id','title','status');
        $query = new Modules();

        if (isset($data['module_id']) && $data['module_id'] != '') {
            $query = $query->where('module_id', 'LIKE', '%' . $data['module_id'] . '%');
        }

        if (isset($data['title']) && $data['title'] != '') {
            $query = $query->where('title', 'LIKE', '%' . $data['title'] . '%');
        }

        if (isset($data['status']) && $data['status'] != '') {
            $query = $query->where('status', 'LIKE', '%' . $data['status'] . '%');
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
            $data[$key][$index++] = $val['module_id'];
            $data[$key][$index++] = $val['title'];
            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive' ;

       
            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'modules/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';



            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'modules/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
   
    public function showModulesForm () {
        $title = 'Add Modules';
        return view('admin.modules.add', compact('title'));
    }

    public function addModule(Request $request) {
        $inputs = $request->all();
      
        $validator = Validator::make($inputs, [
            'module_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $modules = new Modules();
            $modules->module_id= $request->module_id;
            $modules->title= $request->title;
            $modules->description= $request->description;
            $modules->status =isset($request->status) && $request->status == 1 ? 1: 0;

            $modules->save();

            Session::flash('success-message', $request->title . " created successfully !");

            $data['success'] = true;

            return response()->json($data);
        }
    }

    public function editModule(Request $request, $id)
    {
        $module = Modules::find($id);
        $title = 'Edit Modules';
        $inputs  = $request->all();
        if ($module || !empty($module)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'module_id' => 'required',
                    'title' => 'required',
                    'description' => 'required'
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $module->module_id= $request->module_id;
                    $module->title= $request->title;
                    $module->description= $request->description;
                    $module->status =isset($request->status) && $request->status == 1 ? 1: 0;

                    if ($module->save()) {
                        Session::flash('success-message', $module->title . " updated successfully !");
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Module updated successfully !");
                }
            } else {
                return view('admin.modules.edit', compact('module', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'modules');
    }

    public function deleteModule($id)
    {
        $module = Modules::find($id);

        if ($module) {
            if ($module->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    }
}
