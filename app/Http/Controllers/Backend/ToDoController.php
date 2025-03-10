<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ToDo;
use App\Models\User;
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

        $sortColumn = array('name','datetime');
        $query = new ToDo();


        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        // Check if the user is not an Administrator, filter by created_by
        if (!auth()->user()->hasRole('Administrator')) {
            $query = $query->where('created_by', auth()->id());
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

            //$data[$key][$index++] = $val['id'];
            $data[$key][$index++] = '<input type="checkbox" class="row-checkbox" value="' . $val['id'] . '">';
            $data[$key][$index++] = $val['name'];
            $data[$key][$index++] = Carbon::parse($val['datetime'])->format('d-m-Y h:i A');
            $data[$key][$index++] = $val['note'];
            //$data[$key][$index++] = $val['is_completed'] == 1 ? 'Completed' : 'Pending';

            //$data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('todo edit')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'todos/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('todo view')) {

                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'todos/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('todo delete')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . config('constant.ADMIN_URL') . 'todos/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

    public function showTodoForm () {
        $title = 'Add To Do';
        $users = User::where('id', '!=', auth()->id())->select('id', 'firstname', 'lastname')->get();
        return view('admin.todos.add', compact('title','users'));
    }

    public function addTodo(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required|string|max:255',
            'datetime' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'note' => 'nullable|string',
            'action' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $todo = new ToDo();
            $todo->name= $request->name;
            $todo->datetime= $request->datetime;
            $todo->user_id = $request->user_id;
            $todo->note= $request->note;
            $todo->created_by = auth()->id();
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
        $users = User::where('id', '!=', auth()->id())->select('id', 'firstname', 'lastname')->get();
        $todo = ToDo::find($id);
        $title = 'Edit Todo';
        $inputs  = $request->all();
        if ($todo || !empty($todo)) {
            if($inputs) {

                $validator = Validator::make($inputs, [
                    'name' => 'required|string|max:255',
                    'datetime' => 'required|date',
                    'user_id' => 'required|exists:users,id',
                    'note' => 'nullable|string',
                    'action' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                } else {
                    $todo->name= $request->name;
                    $todo->datetime= $request->datetime;
                    $todo->user_id = $request->user_id;
                    $todo->note= $request->note;
                    $todo->updated_by = auth()->id();

                    if ($todo->save()) {
                        Session::flash('success-message', $todo->name . " updated successfully !");
                        $data['success'] = true;
                        return response()->json($data);
                    }
                    return redirect()->back()->with("success", " Todo updated successfully !");
                }
            } else {
                return view('admin.todos.edit', compact('title', 'todo','users'));
                //return view('admin.distributors.edit', compact('distributor', 'title'));
            }

        }
        return Redirect(config('constants.ADMIN_URL') . 'todos');
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
            $object->deleted_by = auth()->id(); // Set deleted_by to current user ID
            $object->save(); // Save the update before deleting
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
            // Update deleted_by before deleting
            ToDo::whereIn('id', $ids)->update(['deleted_by' => auth()->id()]);
            // Perform delete operation
            ToDo::whereIn('id', $ids)->delete();
            return response('TRUE');
            //return response()->json(['message' => 'Records deleted successfully.']);
        }

        return response( 'Invalid action');
    }
}
