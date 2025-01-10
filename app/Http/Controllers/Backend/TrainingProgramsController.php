<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\TrainingPrograms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TrainingProgramsController extends Controller
{
    public function index() {
        $title = 'Manage Trainings';
        return view('admin.trainings.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','name','video_path');
        $query = new TrainingPrograms();


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
            $videoPaths = json_decode($val['video_path']);
            $videoList = implode('<br>', $videoPaths); // Display each video path on a new line
            $data[$key][$index++] = $videoList;


            $action = '';
            $action .= '<div class="d-flex">';


            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'trainings/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';

            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'trainings/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' .  config('constant.ADMIN_URL') . 'trainings/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';

            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function showTrainingForm () {
        $title = 'Add Training Program';
        return view('admin.trainings.add', compact('title'));
    }

    public function addTraining(Request $request) {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required|string|max:255',
            'videos.*' => 'nullable|file|mimes:mp4,avi,mov|max:204800'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());

        } else {

            $training = new TrainingPrograms();
            $training->name = $request->name;

            // Check if videos are uploaded and handle video uploads
            if ($request->hasFile('videos')) {
                $videoPaths = [];
                foreach ($request->file('videos') as $video) {
                    $path = $video->store('training_videos', 'public');
                    $videoPaths[] = $path;
                }

                // Save the video paths as a JSON string or array (depending on your needs)
                $training->video_path = json_encode($videoPaths); // Save multiple paths as JSON (if needed)
            }
            $training->save();
            if ($training->save()) {
                Session::flash('success-message', $request->title . " created successfully !");
                $data['success'] = true;

                return response()->json($data);
            }
            return redirect()->back()->with("success", " Training Program added successfully !");
        }
    }

    public function editTraining(Request $request, $id)
    {
        // Find the training program by ID
        $training = TrainingPrograms::find($id);
        $title = 'Edit Training';

        // If the training program exists
        if ($training) {

            // If the form has been submitted (POST request)
            if ($request->isMethod('post')) {
                $inputs = $request->all();

                // Validate inputs
                $validator = Validator::make($inputs, [
                    'name' => 'required|string|max:255',
                    'videos.*' => 'nullable|file|mimes:mp4,avi,mov|max:204800'
                ]);

                // If validation fails, return errors
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                // Update the name
                $training->name = $request->name;

                // Check if new videos are uploaded
                if ($request->hasFile('videos')) {
                    // Store new videos and add their paths to an array
                    $newVideoPaths = [];
                    foreach ($request->file('videos') as $video) {
                        $path = $video->store('training_videos', 'public');
                        $newVideoPaths[] = $path;
                    }

                    // Merge new video paths with the existing ones (if any)
                    $videoPaths = json_decode($training->video_path, true);
                    $training->video_path = json_encode(array_merge($videoPaths, $newVideoPaths));
                }

                // Save the training program
                if ($training->save()) {
                    // Flash success message and return success response
                    Session::flash('success-message', $request->name . " updated successfully!");
                    return response()->json(['success' => true]);
                }

                // If save fails, return a failure message
                return redirect()->back()->with('error', 'Failed to update the training program.');
            }

            // If it's a GET request, show the edit form with the current data
            $videoPaths = json_decode($training->video_path);
            return view('admin.trainings.edit', compact('title', 'training', 'videoPaths'));
        }

        // If the training program doesn't exist, redirect to the list page
        return redirect()->route('trainings.index')->with('error', 'Training program not found');
    }
    public function removeVideo(Request $request, $id)
    {
        $training = TrainingPrograms::find($id);
        if ($training && $request->has('video_path')) {
            $videoPath = $request->video_path;

            // Remove video from storage
            if (Storage::exists('public/' . $videoPath)) {
                Storage::delete('public/' . $videoPath);
            }

            // Update the video_paths in the database
            $videoPaths = json_decode($training->video_path, true);
            $videoPaths = array_filter($videoPaths, function($path) use ($videoPath) {
                return $path !== $videoPath;
            });

            $training->video_path = json_encode(array_values($videoPaths));

            // Save the updated record
            $training->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Video not found.']);
    }

    public function viewTraining($id) {

        $training = TrainingPrograms::find($id);
        $title = 'View Training ';
        $videoPaths = json_decode($training->video_path);
        return view('admin.trainings.view', compact('title', 'training', 'videoPaths'));
    }
    public function deleteTraining($id)
    {
        $object = TrainingPrograms::find($id);

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
