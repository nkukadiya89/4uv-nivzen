<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DistributorController extends Controller
{
    public function index() {
        $title = 'Manage Distributors';
        return view('admin.distributors.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','enagic_id','name', 'account_status');
        $query = new Distributor();

        if (isset($data['enagic_id']) && $data['enagic_id'] != '') {
            $query = $query->where('enagic_id', 'LIKE', '%' . $data['enagic_id'] . '%');
        }

        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
        }


        if (isset($data['account_status']) && $data['account_status'] != '') {
            $query = $query->where('account_status', 'LIKE', '%' . $data['account_status'] . '%');
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

            $data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['enagic_id'];
            $data[$key][$index++] = $val['name'];

            $data[$key][$index++] = $val['account_status'] == 1 ? 'Active' : 'Inactive' ;


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';


            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'distributors/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'distributors/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'distributors/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

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
        return view('admin.distributors.add', compact('title'));
    }

    public function addDistributor(Request $request) {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'enagic_id' => 'required',
            'mobile_no' => 'required',
            'email' => 'required',
            'name' => 'required',
            'address' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'type' => 'required',
            'distributor_status' => 'required',
            'goal_for' => 'required',
            'upline_name' => 'required',
            'leader_name' => 'required',
            'account_status' => 'required',
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $distributor = new Distributor();
            $distributor->enagic_id= $request->enagic_id;
            $distributor->mobile_no= $request->mobile_no;
            $distributor->email= $request->email;
            $distributor->name= $request->name;
            $distributor->address= $request->address;
            $distributor->area= $request->area;
            $distributor->city= $request->city;
            $distributor->state= $request->state;
            $distributor->country= $request->country;
            $distributor->country= $request->country;
            $distributor->type = $request->type;
            $distributor->distributor_status = $request->distributor_status;
            $distributor->upline_name = $request->upline_name;
            $distributor->leader_name = $request->leader_name;
            $distributor->account_status = $request->account_status;

            $distributor->save();

            Session::flash('success-message', $request->title . " created successfully !");

            $data['success'] = true;
             print_r($data);
            return response()->json($data);
        }
    }

    public function editDistributor(Request $request, $id)
    {

        $distributor = Distributor::find($id);
        $title = 'Edit Distributor';
        $inputs  = $request->all();
        if ($distributor || !empty($distributor)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'enagic_id' => 'required',
                    'mobile_no' => 'required',
                    'email' => 'required',
                    'name' => 'required',
                    'address' => 'required',
                    'area' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'type' => 'required',
                    'distributor_status' => 'required',
                    'goal_for' => 'required',
                    'upline_name' => 'required',
                    'leader_name' => 'required',
                    'account_status' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $distributor->enagic_id= $request->enagic_id;
                    $distributor->mobile_no= $request->mobile_no;
                    $distributor->email= $request->email;
                    $distributor->name= $request->name;
                    $distributor->address= $request->address;
                    $distributor->area= $request->area;
                    $distributor->city= $request->city;
                    $distributor->state= $request->state;
                    $distributor->country= $request->country;
                    $distributor->country= $request->country;
                    $distributor->type = $request->type;
                    $distributor->distributor_status = $request->distributor_status;
                    $distributor->upline_name = $request->upline_name;
                    $distributor->leader_name = $request->leader_name;
                    $distributor->account_status = $request->account_status;

                    if ($distributor->save()) {
                        Session::flash('success-message', $distributor->name . " updated successfully !");
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Distributor updated successfully !");
                }
            } else {
                return view('admin.distributors.edit', compact('distributor', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'distributors');
    }

    public function viewDistributor($id) {

        $distributor = Distributor::find($id);
        $title = 'View Distributor ';
        return view('admin.distributors.view', compact('title', 'distributor'));
    }
    public function deleteDistributor($id)
    {
        $object = Distributor::find($id);

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
}
