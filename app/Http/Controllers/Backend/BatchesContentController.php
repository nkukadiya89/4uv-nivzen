<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Batches;
use App\Models\BatchesContent;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class BatchesContentController extends Controller
{
    /**
     * List function
     *
     * @param Request $request
     * @return void
     */
    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','title','content_type','Video Link','', 'status');
        $query = new BatchesContent();

       

        if (isset($data['title']) && $data['title'] != '') {
            $query = $query->where('title', 'LIKE', '%' . $data['title'] . '%');
        }
        if (isset($data['content_type']) && $data['content_type'] != '') {
            $query = $query->where('content_type', 'LIKE', '%' . $data['content_type'] . '%');
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
            $data[$key][$index++] = $val['title'];
            $data[$key][$index++] = $val['content_type'];

            $data[$key][$index++] = $val['video_link'];

            $data[$key][$index++] = $val['description'];

          
            $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive' ;

       
            // $data[$key][$index++] = $val['status'] == 1 ? 'Active' : 'Inactive';
            $action = '';
            $action .= '<div class="d-flex">';

         
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'batches-content/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' .route('batche-content-view',  $val['id']). '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .route('batche-content-delete',  $val['id']). '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
   
    /**
     * Show batche content function
     *
     * @param [int] $id
     * @return void
     */
    public function showBatcheContentForm ($id) {
        $title = 'Add Batche Content';
        return view('admin.batches-content.add', compact('title', 'id'));
    }

    /**
     * Add batche content function
     *
     * @param Request $request
     * @param [int] $id
     * @return void
     */
    public function addBatcheContent(Request $request, $id) {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'title' => 'required',
            'content_type'=> 'required'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $batche = new BatchesContent();
            $batche->batch_id= $id;
            $batche->title= $request->title;
            $batche->content_type = $request->content_type;
            $batche->video_link= $request->video_link;
            $batche->description= $request->description;
            $batche->status =isset($request->status) && $request->status == 1 ? 1: 0;

            $batche->save();

            Session::flash('success-message', $request->title . " created successfully !");

            $data['success'] = true;

            return response()->json($data);
        }
    }

    /**
     * Edit Batche function
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function editBatche(Request $request, $id)
    {

        $batche = Batches::find($id);
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

    /**
     * View Batche Content function
     *
     * @param [type] $id
     * @return void
     */
    public function viewBatcheContent($id) {

        $batche = BatchesContent::find($id);
        $title = 'View Batches Content ';
        return view('admin.batches-content.view', compact('title', 'batche'));
    }

    /**
     * Delete function
     *
     * @param [type] $id
     * @return void
     */
    public function deleteBatcheContent($id)
    {
        $object = BatchesContent::find($id);

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
    
    /**
     * Upload Large Files function
     *
     * @param Request $request
     * @return void
     */
    public function uploadLargeFiles(Request $request) {
       
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
    
        if (!$receiver->isUploaded()) {
            // file not uploaded
        }
    
        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
    
            $disk = Storage::disk(config('filesystems.default'));
            $path = $disk->putFileAs('videos', $file, $fileName);
    
            // delete chunked file
            unlink($file->getPathname());
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
        }
    
        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
}
