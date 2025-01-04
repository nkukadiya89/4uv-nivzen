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

class CoursesController extends Controller
{

    public function index()
    {
        $title = 'Manage Course';
        return view('admin.course.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','course_id','title','status');
        $query = new Course();

        if (isset($data['course_id']) && $data['course_id'] != '') {
            $query = $query->where('course_id', 'LIKE', '%' . $data['course_id'] . '%');
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
            $data[$key][$index++] = $val['course_id'];
            $data[$key][$index++] = $val['title'];
            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive' ;

       
            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'courses/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'courses/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
   
    public function showCoursesForm () {
        $title = 'Add Course';
        $modules = Modules::pluck('title','id');
        return view('admin.course.add', compact('title', 'modules'));
    }

    public function addCourse(Request $request) {
        $inputs = $request->all();

       
        $validator = Validator::make($inputs, [
            'course_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $course = new Course();
            $course->course_id= $request->course_id;
            $course->title= $request->title;
            $course->description= $request->description;
            $course->status =isset($request->status) && $request->status == 1 ? 1: 0;
            $course->save();
            $course->modules()->attach($request->course_modules); 

            Session::flash('success-message', $request->title . " created successfully !");

            $data['success'] = true;

            return response()->json($data);
        }
    }

    public function editCourse(Request $request, $id)
    {
        $course = Course::find($id);
        $title = 'Edit Course';
        $inputs  = $request->all();
        $selected_modules = $course->modules->pluck('id')->toArray();
        $modules = Modules::pluck('title','id');

        if ($course || !empty($course)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'course_id' => 'required',
                    'title' => 'required',
                    'description' => 'required'
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $course->course_id= $request->course_id;
                    $course->title= $request->title;
                    $course->description= $request->description;
                    $course->status =isset($request->status) && $request->status == 1 ? 1: 0;

                    $course->modules()->detach(); 
                    $course->modules()->attach($request->course_modules); 
                    
                    if ($course->save()) {
                        Session::flash('success-message', $course->title . " updated successfully !");
                        $data['success'] = true;

                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Course updated successfully !");
                }
            } else {
                return view('admin.course.edit', compact('course', 'title', 'modules', 'selected_modules'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'course');
    }

    public function deleteCourse($id)
    {
        $object = Course::find($id);

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
