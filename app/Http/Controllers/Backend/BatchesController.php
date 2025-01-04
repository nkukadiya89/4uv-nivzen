<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BatchesController extends Controller
{

    public function index()
    {
        $title = 'Manage Batches';
        return view('admin.batches.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','batch_id','title','start_date', 'end_date', 'status');
        $query = new Batch();

        if (isset($data['batch_id']) && $data['batch_id'] != '') {
            $query = $query->where('batch_id', 'LIKE', '%' . $data['batch_id'] . '%');
        }

        if (isset($data['title']) && $data['title'] != '') {
            $query = $query->where('title', 'LIKE', '%' . $data['title'] . '%');
        }

        if (isset($data['date_range_1']) && $data['date_range_1'] != '') {
            $date_range_1 = explode('→', trim($data['date_range_1']));
            $query = $query->where(DB::raw('DATE(batches.start_date)'), '>=', trim(date('Y-m-d',strtotime(trim($date_range_1[0])))));
            $query = $query->where(DB::raw('DATE(batches.start_date)'), '<=', trim(date('Y-m-d',strtotime(trim($date_range_1[1])))));
        }

        if (isset($data['date_range_2']) && $data['date_range_2'] != '') {
            $date_range_2 = explode('→', trim($data['date_range_2']));
            $query = $query->where(DB::raw('DATE(batches.end_date)'), '>=', trim(date('Y-m-d',strtotime(trim($date_range_2[0])))));
            $query = $query->where(DB::raw('DATE(batches.end_date)'), '<=', trim(date('Y-m-d',strtotime(trim($date_range_2[1])))));      
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
        
        $arrUsers = $users->toArray();
        $data = array();
        $style1 = 'border:2px; width:50px;height: 13px;display: inline-block;';//background-color:#28C8FB;';

        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;

            $data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['batch_id'];
            $data[$key][$index++] = $val['title'];
            $data[$key][$index++] = date('d F, Y H:i', strtotime($val['start_date'])); 

            $data[$key][$index++] = date('d F, Y H:i', strtotime($val['end_date'])); 

            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive' ;

       
            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'batches/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'batches/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'batches/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
   
    public function showBatcheForm () {
        $title = 'Add Batche';
        $courses = Course::pluck('title','id');
        return view('admin.batches.add', compact('title', 'courses'));
    }

    public function addBatche(Request $request) {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'batch_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $batche = new Batch();
            $batche->batch_id= $request->batch_id;
            $batche->title= $request->title;
            $batche->start_date = Carbon::create($request->start_date);
            $batche->end_date = Carbon::create($request->end_date);
            $batche->course_id= $request->course_id;
            $batche->description= $request->description;
            $batche->status =isset($request->status) && $request->status == 1 ? 1: 0;

            $batche->save();

            Session::flash('success-message', $request->title . " created successfully !");

            $data['success'] = true;

            return response()->json($data);
        }
    }

    public function editBatche(Request $request, $id)
    {

        $batche = Batch::find($id);
        $title = 'Edit Batche';
        $inputs  = $request->all();
        if ($batche || !empty($batche)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'batch_id' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'course_id' => 'required'
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $batche->batch_id= $request->batch_id;
                    $batche->title= $request->title;
                    $batche->start_date = Carbon::create($request->start_date);
                    $batche->end_date = Carbon::create($request->end_date);
                    $batche->course_id= $request->course_id;
                    $batche->description= $request->description;
                    $batche->status =isset($request->status) && $request->status == 1 ? 1: 0;

                    if ($batche->save()) {
                        Session::flash('success-message', $batche->title . " updated successfully !");
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Batch updated successfully !");
                }
            } else {
                $courses = Course::pluck('title','id');
                return view('admin.batches.edit', compact('batche', 'title', 'courses'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'batches');
    }

    public function viewBatche($id) {

        $batche = Batch::find($id);
        $title = 'View Batches ';
        return view('admin.batches.view', compact('title', 'batche'));
    }
    public function deleteCourse($id)
    {
        $object = Batch::find($id);

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
