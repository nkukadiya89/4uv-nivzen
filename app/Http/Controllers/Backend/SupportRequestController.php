<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class SupportRequestController extends Controller
{
    public function index() {
        $title = 'Manage Support';
        return view('admin.support.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','support_name','request_number');
        $query = SupportRequest::with(['fromUser', 'toUser']);


        if (isset($data['support_name']) && $data['support_name'] != '') {
            $query = $query->where('support_name', 'LIKE', '%' . $data['support_name'] . '%');
        }

        if (isset($data['request_number']) && $data['request_number'] != '') {
            $query = $query->where('request_number', 'LIKE', '%' . $data['request_number'] . '%');
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
            $data[$key][$index++] = $val['support_name'];
            $data[$key][$index++] = $val['request_number'];

            //$data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';


            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';


            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'support/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'support/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'support/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function create() {
        $title = 'Add Support';
        $users = User::pluck('name','id');
        return view('admin.support.create', compact('title', 'users'));
    }

    public function store(Request $request) {
        $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'support_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Generate unique request number
        $requestNumber = 'REQ-' . strtoupper(uniqid());

        $supportRequest = SupportRequest::create([
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
            'support_name' => $request->support_name,
            'description' => $request->description,
            'request_number' => $requestNumber,
        ]);

        // Send email alert to the recipient
        $toUser = User::find($request->to_user_id);

        Mail::raw("You have received a new support request: {$requestNumber}", function ($message) use ($toUser) {
            $message->to($toUser->email)
                ->subject('New Support Request');
        });

        return redirect()->back()->with('success', "Request submitted! Request Number: {$requestNumber}");
    }
    public function edit($id)
    {
        $title = 'Edit Support';
        $supportRequest = SupportRequest::with(['fromUser', 'toUser'])->findOrFail($id);
        $users = User::all(); // Fetch users to populate dropdowns
        return view('admin.support.edit', compact('title', 'supportRequest', 'users'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'support_name' => 'required|string|max:255',
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $supportRequest = SupportRequest::findOrFail($id);
        $supportRequest->update([
            'support_name' => $request->input('support_name'),
            'from_user_id' => $request->input('from_user_id'),
            'to_user_id' => $request->input('to_user_id'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('support-requests.edit', $id)->with('success', 'Support Request updated successfully.');
    }

    public function viewSupport($id) {

        $support = SupportRequest::find($id);
        $title = 'View Support ';
        return view('admin.support.view', compact('title', 'support'));
    }
    public function deleteSupport($id)
    {
        $object = SupportRequest::find($id);

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
