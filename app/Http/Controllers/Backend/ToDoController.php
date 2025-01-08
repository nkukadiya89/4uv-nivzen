<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ToDo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    public function index() {
        $title = 'Manage To Do';
        return view('admin.todos.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','name');
        $query = new ToDo();


        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
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
            $data[$key][$index++] = $val['name'];
            $data[$key][$index++] = $val['date'];
            $data[$key][$index++] = $val['time'];
            $data[$key][$index++] = $val['note'];
            $data[$key][$index++] = $val['is_completed'] == 1 ? 'Completed' : 'Pending';

            //$data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';


            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'todos/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'todos/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'todos/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function showTodoForm () {
        $title = 'Add To Do';
        return view('admin.todos.add', compact('title'));
    }

    public function addTodo(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'customer_list' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {
            $todo = new ToDo();
            $todo->name= $request->name;
            $todo->date= $request->date;
            $todo->time= $request->time;
            $todo->customer_list= $request->customer_list;
            $todo->note= $request->note;
            $todo->user_id = auth()->id();
            $todo->save();

            if ($todo->save()) {
                Session::flash('success-message', $request->name . " created successfully !");
                $data['success'] = true;

                return response()->json($data);
            }
            return redirect()->back()->with("success", " Todo added successfully !");
        }
    }

    public function editTodo(Request $request, $id)
    {

        $todo = ToDo::find($id);
        $title = 'Edit Todo';
        $inputs  = $request->all();
        if ($todo || !empty($todo)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'name' => 'required|string|max:255',
                    'date' => 'nullable|date',
                    'time' => 'nullable|date_format:H:i',
                    'customer_list' => 'nullable|string',
                    'note' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $todo->name= $request->name;
                    $todo->date= $request->date;
                    $todo->time= $request->time;
                    $todo->customer_list= $request->customer_list;
                    $todo->note= $request->note;

                    if ($todo->save()) {
                        Session::flash('success-message', $todo->name . " updated successfully !");
                        $data['success'] = true;
                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Todo updated successfully !");
                }
            } else {
                return view('admin.todos.edit', compact('title', 'todo'));
                //return view('admin.distributors.edit', compact('distributor', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'prospects');
    }

    public function viewTodo($id) {

        $todo = ToDo::find($id);
        $title = 'View Todo ';
        return view('admin.todos.view', compact('title', 'todo'));
    }
    public function deleteTodo($id)
    {
        $object = ToDo::find($id);

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
