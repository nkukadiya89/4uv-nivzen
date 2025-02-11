<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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
        ]);

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
            $prospect->save();

            if ($prospect->save()) {
                Session::flash('success-message', $request->name . " created successfully !");
                $data['success'] = true;

                return response()->json($data);
            }
            return redirect()->back()->with("success", " Prospect added successfully !");
        }
    }

    public function editProspect(Request $request, $id)
    {

        $prospect = Prospect::find($id);
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
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    $prospect->name= $request->name;
                    $prospect->email= $request->email;
                    $prospect->mobile_no= $request->mobile_no;
                    $prospect->address= $request->address;
                    $prospect->area= $request->area;
                    $prospect->city= $request->city;
                    $prospect->state= $request->state;
                    $prospect->country= $request->country;

                    if ($prospect->save()) {
                        Session::flash('success-message', $prospect->name . " updated successfully !");
                        $data['success'] = true;
                        return response()->json($data);
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

    public function viewProspect($id) {

        $prospect = Prospect::find($id);
        $title = 'View Prospect ';
        return view('admin.prospects.view', compact('title', 'prospect'));
    }

    public function deleteProspect($id)
    {
        $object = Prospect::find($id);

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
            Prospect::whereIn('id', $ids)->delete();
            return response('TRUE');
            //return response()->json(['message' => 'Records deleted successfully.']);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }
}
