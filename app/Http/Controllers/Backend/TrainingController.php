<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\VideoLesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizResult;
use App\Models\UserTrainingActivity;
use App\Rules\AtLeastOneCorrectAnswer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class TrainingController extends Controller
{
    public function index() {
        $title = 'Manage Trainings';
        return view('admin.trainings.manage', compact('title'));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('id','name', 'video_lessons_count', 'quiz_questions_count');
        $query = Training::withCount(['videoLessons','quizzes as quiz_questions_count']);

        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        if (!empty($data['video_lessons_count'])) {
            $query->having('video_lessons_count', '=', $data['video_lessons_count']);
        }

        if (!empty($data['quiz_questions_count'])) {
            $query->having('quiz_questions_count', '=', $data['quiz_questions_count']);
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
            $data[$key][$index++] = $val['video_lessons_count'] ?? 0; // Count of videos
            $data[$key][$index++] = $val['quiz_questions_count'];
//            $videoPaths = json_decode($val['video_path']);
//            $videoList = implode('<br>', $videoPaths); // Display each video path on a new line
//            $data[$key][$index++] = $videoList;


            $action = '';
            $action .= '<div class="d-flex">';

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('training edit')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'trainings/edit/' . $val['id'] . '" title="view"><i class="la la-edit"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('training view')) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . config('constant.ADMIN_URL') . 'trainings/view/' . $val['id'] . '" title="view"><i class="la la-eye"></i> </a>';
            }

            if (auth()->user()->hasRole('Administrator') || auth()->user()->can('training delete')) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . config('constant.ADMIN_URL') . 'trainings/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

    public function showTrainingForm () {
        $title = 'Add Training Program';
        return view('admin.trainings.add', compact('title'));
    }

    public function addTraining(Request $request)
    {
        $inputs = $request->all();

        // Preprocess data to add `is_correct` to options
        if (!empty($inputs['videos'])) {
            foreach ($inputs['videos'] as &$video) {
                if (!empty($video['quizzes'])) {
                    foreach ($video['quizzes'] as &$quiz) {
                        $correctOptionIndex = $quiz['correct_option'] ?? null;

                        if (!empty($quiz['options'])) {
                            foreach ($quiz['options'] as $index => &$option) {
                                // Set is_correct based on correct_option index
                                $option['is_correct'] = ($index == $correctOptionIndex);
                            }
                        }
                        // Remove correct_option to avoid conflicts
                        unset($quiz['correct_option']);
                    }
                }
            }
        }

        // Validation
        $validator = Validator::make($inputs, [
            'name' => 'required|string|max:255',
            'videos' => 'required|array',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.description' => 'nullable|string',
            'videos.*.video' => 'required|file|mimes:mp4,mov,avi,flv|max:204800',
            'videos.*.thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos.*.quizzes' => 'nullable|array',
            'videos.*.quizzes.*.question' => 'required|string',
            'videos.*.quizzes.*.options' => 'required|array|min:2',
            'videos.*.quizzes.*.options.*.option' => 'required|string',
            'videos.*.quizzes.*.options.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Database transaction
        DB::beginTransaction();

        try {
            $training = Training::create(['name' => $validated['name']]);

            foreach ($validated['videos'] as $videoData) {
                $videoPath = $videoData['video']->store('videos', 'public');
                $thumbnailPath = isset($videoData['thumbnail'])
                    ? $videoData['thumbnail']->store('thumbnails', 'public')
                    : null;

                $videoLesson = VideoLesson::create([
                    'training_id' => $training->id,
                    'title' => $videoData['title'],
                    'description' => $videoData['description'] ?? '',
                    'video_url' => $videoPath,
                    'thumbnail_url' => $thumbnailPath,
                ]);

                if (isset($videoData['quizzes'])) {
                    foreach ($videoData['quizzes'] as $quizData) {
                        $quiz = Quiz::create([
                            'video_lesson_id' => $videoLesson->id,
                            'question' => $quizData['question'],
                        ]);

                        foreach ($quizData['options'] as $optionData) {
                            QuizOption::create([
                                'quiz_id' => $quiz->id,
                                'option' => $optionData['option'],
                                'is_correct' => $optionData['is_correct'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with("success", " Training Program added successfully !");
            //return response()->json(['message' => 'Training created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding training: ' . $e->getMessage());
            return response()->json(['error' => 'There was an issue adding the training.'], 500);
        }
    }

    public function editTraining(Request $request, $id)
    {
        // Fetch the training with its related videos, quizzes, and options
        //$training = Training::with(['videoLessons.quizzes.options'])->findOrFail($id);
        $training = Training::with(['videoLessons.quizzes' => function ($query) {
            $query->orderBy('id'); // Order quizzes by ID
        }, 'videoLessons.quizzes.options' => function ($query) {
            $query->orderBy('id'); // Order options by ID
        }])->find($id);
        $title = 'Edit Training';

        // If it's a GET request (view the form)
        if ($request->isMethod('get')) {
            // Return the edit view with the training data
            return view('admin.trainings.edit', compact('title', 'training'));
        }

        // If it's a POST or PUT request (form submission)
        if ($request->isMethod('post') || $request->isMethod('put')) {
            // Validate the incoming request
            $inputs  = $request->all();
            $validator = Validator::make($inputs, [
                'name' => 'required|string|max:255',
                'videos.*.title' => 'required|string|max:255',
                'videos.*.description' => 'nullable|string|max:255',
                'videos.*.quizzes.*.question' => 'required|string|max:255',
                'videos.*.quizzes.*.options.*.option' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                // Update the training name
                $training->name = $request->input('name');
                //$training->save();

                // Process each video
                foreach ($request->input('videos', []) as $videoIndex => $videoData) {
                    // Update existing video (do not replace video file)
                    $video = isset($videoData['id']) ? VideoLesson::find($videoData['id']) : new VideoLesson();
                    $video->training_id = $training->id;
                    $video->title = $videoData['title'];
                    $video->description = $videoData['description'];

                    // Check if a new video is uploaded
                    if ($request->hasFile("videos.{$videoIndex}.video")) {
                        $videoFile = $request->file("videos.{$videoIndex}.video");
                        $videoPath = $videoFile->store('videos', 'public'); // Store the new video file
                        $video->video_url = $videoPath; // Save the video URL or path
                    }

                    // Check if a new thumbnail is uploaded
                    if ($request->hasFile("videos.{$videoIndex}.thumbnail")) {
                        $thumbnailFile = $request->file("videos.{$videoIndex}.thumbnail");
                        $thumbnailPath = $thumbnailFile->store('thumbnails', 'public'); // Store the new thumbnail
                        $video->thumbnail_url = $thumbnailPath; // Save the thumbnail path
                    }

                    // Save the video data (without replacing the video or thumbnail if not uploaded)
                    $video->save();

                    // Process quizzes for the video (update only questions and answers)
                    if (isset($videoData['quizzes']) && is_array($videoData['quizzes'])) {
                        foreach ($videoData['quizzes'] as $quizIndex => $quizData) {
                            // Update existing quiz or create a new one
                            $quiz = isset($quizData['id']) ? Quiz::find($quizData['id']) : new Quiz();
                            $quiz->video_lesson_id = $video->id;
                            $quiz->question = $quizData['question'];
                            $quiz->save();

                            // Process options for the quiz (update only options)
                            foreach ($quizData['options'] as $optionIndex => $optionData) {
                                $option = QuizOption::updateOrCreate(
                                    [
                                        'quiz_id' => $quiz->id,
                                        'id' => $optionData['id'] ?? null,
                                    ],
                                    [
                                        'option' => $optionData['option'],
                                        'is_correct' => ($quizData['correct_option'] == $optionIndex),
                                    ]
                                );
                            }
                        }
                    }
                }

                if ($training->save()) {
                    Session::flash('success-message', $training->name . " updated successfully !");
                    $data['success'] = true;

                    return response()->json($data);
                }
                return redirect()->back()->with("success", " Training updated successfully !");

            }
        }

        // If none of the above conditions are met, redirect to the list page
        return redirect(config('constants.ADMIN_URL') . 'trainings');
    }


    public function deleteVideo($trainingId, $videoLessonId)
    {
        $training = Training::find($trainingId);
        $videoLesson = $training->videoLessons()->find($videoLessonId);

        if ($videoLesson) {
            // Step 1: Delete the video file if it exists
            if ($videoLesson->video_url && Storage::exists('public/' . $videoLesson->video_url)) {
                Storage::delete('public/' . $videoLesson->video_url);
            }

            // Step 2: Delete the associated quizzes and quiz options
            $videoLesson->quizzes->each(function ($quiz) {
                // Delete quiz options
                $quiz->options()->delete();
                // Delete the quiz
                $quiz->delete();
            });

            // Step 3: Delete the video lesson
            $videoLesson->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Video lesson not found.']);
    }

    public function deleteQuiz($quizId)
    {
        $quiz = Quiz::find($quizId);
        if ($quiz) {
            // Delete options
            foreach ($quiz->options as $option) {
                $option->delete();
            }

            // Delete quiz
            $quiz->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Quiz not found']);
    }

    public function deleteQuizOption($quizOptionId)
    {
        $quizOption = QuizOption::find($quizOptionId);
        if ($quizOption) {
            // Delete options
//            foreach ($quiz->options as $option) {
//                $option->delete();
//            }

            // Delete quiz
            $quizOption->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Quiz not found']);
    }


    public function viewTraining($id) {

        $training = Training::with('videoLessons.quizzes')->find($id);
        $title = 'View Training ';

        $videoPaths = $training->videoLessons->pluck('video_url');
        // Calculate the total question count
        $totalQuestions = $training->videoLessons->flatMap(function ($lesson) {
            return $lesson->quizzes;
        })->count();
//        dd($totalQuestions);
//        exit;
        return view('admin.trainings.view', compact('title', 'training', 'videoPaths','totalQuestions'));
    }

    public function deleteTraining($id)
    {
        $object = Training::find($id);

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
            Training::whereIn('id', $ids)->delete();
            return response('TRUE');
            //return response()->json(['message' => 'Records deleted successfully.']);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }

    // Store training activity when a video lesson is completed
    public function storeActivity(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_id' => 'required|exists:trainings,id',
            'video_lesson_id' => 'required|exists:video_lessons,id',
            'completed_at' => 'required|date',
        ]);

        // Store the activity in the database
        UserTrainingActivity::create([
            'user_id' => $validated['user_id'],
            'training_id' => $validated['training_id'],
            'video_lesson_id' => $validated['video_lesson_id'],
            'completed_at' => $validated['completed_at'],
        ]);

        return response()->json(['success' => true]);
    }

    // Fetch quiz questions for a given video lesson
    public function getQuizQuestions(Request $request)
    {
        $videoLessonId = $request->query('video_lesson_id');

        // Fetch the quiz questions related to the video lesson
        $quizzes = Quiz::where('video_lesson_id', $videoLessonId)
            ->with('options')
            ->get();

        return response()->json([
            'success' => true,
            'questions' => $quizzes,
        ]);
    }

    public function submitQuizAnswers(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'video_lesson_id' => 'required|exists:video_lessons,id',
            'answers' => 'required|array',
            'answers.*.quiz_id' => 'required|exists:quizzes,id',
            'answers.*.option_id' => 'required|exists:quiz_options,id',
        ]);

        $userId = $data['user_id'];
        $videoLessonId = $data['video_lesson_id'];
        $answers = $data['answers'];

        $correctAnswers = 0;

        foreach ($answers as $answer) {
            $quizOption = QuizOption::find($answer['option_id']);
            if ($quizOption && $quizOption->is_correct) {
                $correctAnswers++;
            }

            // Save user answers
            UserQuizAnswer::create([
                'user_id' => $userId,
                'quiz_id' => $answer['quiz_id'],
                'option_id' => $answer['option_id'],
                'is_correct' => $quizOption->is_correct ?? false,
            ]);
        }

        // Calculate and save results
        $totalQuestions = count($answers);
        $score = ($correctAnswers / $totalQuestions) * 100;

        UserQuizResult::create([
            'user_id' => $userId,
            'video_lesson_id' => $videoLessonId,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score' => $score,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz submitted successfully!',
            'score' => $score,
        ]);
    }


//    public function removeVideo(Request $request, $id)
//    {
//        $training = Training::find($id);
//        if ($training && $request->has('video_path')) {
//            $videoPath = $request->video_path;
//
//            // Remove video from storage
//            if (Storage::exists('public/' . $videoPath)) {
//                Storage::delete('public/' . $videoPath);
//            }
//
//            // Update the video_paths in the database
//            $videoPaths = json_decode($training->video_path, true);
//            $videoPaths = array_filter($videoPaths, function($path) use ($videoPath) {
//                return $path !== $videoPath;
//            });
//
//            $training->video_path = json_encode(array_values($videoPaths));
//
//            // Save the updated record
//            $training->save();
//
//            return response()->json(['success' => true]);
//        }
//
//        return response()->json(['success' => false, 'message' => 'Video not found.']);
//    }
//    public function addTraining(Request $request)
//    {
//
//        $inputs = $request->all();
//        $validator = Validator::make($inputs, [
//            'name' => 'required|string|max:255',
//            'video' => 'required|mimes:mp4,avi,mkv|max:204800',
//            'questions' => 'required|array',
//            'questions.*.question' => 'required|string',
//            'questions.*.options' => 'required|array',
//            'questions.*.correct' => [
//                'required',
//                'array',
//                function ($attribute, $value, $fail) {
//                    if (empty($value)) {
//                        $fail('At least one correct answer must be selected for each question.');
//                    }
//                },
//            ],
//        ]);
//
//
//        if ($validator->fails()) {
//            return json_encode($validator->errors());
//
//        } else {
//            // Upload the video
//            $videoPath = $request->file('video')->store('videos', 'public');
//
//            // Create the training
//            $training = Training::create([
//                'name' => $request->input('name'),
//            ]);
//
//            // Create video lesson and link to training
//            $videoLesson = VideoLesson::create([
//                'training_id' => $training->id,
//                'title' => 'Video Lesson', // Add title if needed
//                'description' => 'Lesson Description', // Add description if needed
//                'video_url' => $videoPath,
//            ]);
//
//            // Save questions and options
//            foreach ($request->input('questions') as $index => $questionData) {
//                $quiz = Quiz::create([
//                    'video_lesson_id' => $videoLesson->id,
//                    'question' => $questionData['question'],
//                ]);
//
//                foreach ($questionData['options'] as $key => $option) {
//                    QuizOption::create([
//                        'quiz_id' => $quiz->id,
//                        'option' => $option,
//                        'is_correct' => in_array($key, $questionData['correct']) ? true : false,
//                    ]);
//                }
//            }
//           // return Redirect(config('constants.ADMIN_URL') . 'trainings');
//            return redirect()->back()->with("success", " Prospect added successfully !");
//        }
//    }
}
