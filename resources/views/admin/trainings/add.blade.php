@extends('admin.layouts.master')
@section('content')
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <!-- Subheader -->
        <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{$title}}</h5>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="p-6 flex-fill">
            <div class="card card-custom gutter-b example example-compact">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" id="frmAdd" method="POST" action="{{ route('training-add') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Training Name -->
                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label" for="trainingName" >Training Name<span class="required">*</span></label>
                            <div class="col-lg-6">
                               <input type="text" name="name" id="trainingName" class="form-control required" placeholder="Enter training name"  />
                            </div>
                        </div>
                        <div id="video-section">
                            <!-- First Video -->

                        </div>

                        <!-- Add Video Button -->
                        <button type="button" class="btn btn-success mb-4" onclick="addVideo()">Add Another Video</button>

                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('trainings-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('custom_js')
    <script>
        let videoIndex = 0; // Start with 1 as the first video is already there
        let questionIndex = { 0: 1 }; // Track question indexes for each video
        let optionIndex = { 0: { 0: 2 } }; // Track option indexes for each question of each video
        window.onload = function() {
            addVideo(); // Call the addVideo function when the page loads
        };
        function addVideo() {
            const videoSection = document.getElementById("video-section");

            // Create new video container
            const videoDiv = document.createElement("div");
            videoDiv.className = "video-container mb-4 border p-3 rounded";
            videoDiv.setAttribute("id", `video-${videoIndex}`);
            videoDiv.innerHTML = `
        <h4 class="d-flex justify-content-between align-items-center">Video ${videoIndex + 1} <button type="button" class="btn btn-danger btn-sm " onclick="removeVideo(${videoIndex})">Remove</button></h4>
        <!-- Video Title -->
                <div class="form-group row">
                      <label class="col-lg-3 form-label">Video Title<span class="required">*</span></label>
                      <div class="col-lg-6">
                         <input type="text" name="videos[${videoIndex}][title]" class="form-control required" placeholder="Enter video title"  />
                      </div>
                </div>
                <!-- Video Description -->
                <div class="form-group row">
                      <label class="col-lg-3 form-label">Video Description</label>
                      <div class="col-lg-6">
                           <textarea name="videos[${videoIndex}][description]" class="form-control" rows="3" placeholder="Enter video description"></textarea>
                      </div>
                </div>
                <!-- Video File -->
                <div class="form-group row">
                         <label class="col-lg-3 form-label">Upload Video<span class="required">*</span></label>
                         <div class="col-lg-6">
                            <input type="file" name="videos[${videoIndex}][video]" class="form-control required" accept="video/*"  />
                        </div>
                </div>
                <!-- Thumbnail -->
                <div class="form-group row">
                         <label class="col-lg-3 form-label">Upload Thumbnail</label>
                         <div class="col-lg-6">
                            <input type="file" name="videos[${videoIndex}][thumbnail]" class="form-control" accept="image/*" />
                          </div>
                </div>
                <!-- MCQ Section -->
                <div class="mcq-section mb-4">
                <h5 class="mb-3">Questions</h5>
                <div id="questions-container-${videoIndex}"></div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion(${videoIndex})">Add Question</button>
                </div>
                `;
    videoSection.appendChild(videoDiv);

    // Initialize question and option indexes for this video
    questionIndex[videoIndex] = 0;
    optionIndex[videoIndex] = {};
    videoIndex++;
}
function removeVideo(index) {
     let videoElement = document.getElementById(`video-${index}`);
    if (videoElement) {
        videoElement.remove();
    } else {
        console.warn(`Video element with ID video-${index} not found.`);
    }
}

function addQuestion(videoId) {
    const questionsContainer = document.getElementById(`questions-container-${videoId}`);
    const questionId = questionIndex[videoId]++; // Increment question index for this video

    // Initialize option tracking for this question
    optionIndex[videoId][questionId] = 0;

    // Add a new question section
    const questionDiv = document.createElement("div");
    questionDiv.id = `question-container-${videoId}-${questionId}`;
    questionDiv.classList.add("mb-4", "border", "p-3", "rounded"); // Add spacing and styling
    questionDiv.innerHTML = `
                <div class="form-group row">
                <div class="col-lg-12">
                <h4>Question ${questionId + 1} <button type="button" class="btn btn-danger btn-sm float-right" onclick="removeQuestion(${videoId}, ${questionId})">Remove</button></h4>
                </div>
                </div>

                <div class="form-group row">
                <label class="col-lg-3 col-form-label" for="question-${videoId}-${questionId}">Question<span class="required">*</span></label>
                <div class="col-lg-9">
                <input type="text"
                id="question-${videoId}-${questionId}"
                name="videos[${videoId}][quizzes][${questionId}][question]"
                class="form-control required"
                placeholder="Enter question"
                 />
                </div>
                </div>

                <div class="options-container">
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addOption(${videoId}, ${questionId})">Add Option</button>
                </div>
                `;

    questionsContainer.appendChild(questionDiv);
}

function removeQuestion(videoIdx, questionIdx) {
    document.getElementById(`question-container-${videoIdx}-${questionIdx}`).remove();
}
function addOption(videoId, questionId) {
    const optionsContainer = document.querySelector(`#question-container-${videoId}-${questionId} .options-container`);

    // Ensure optionsContainer exists
    if (!optionsContainer) {
        console.error(`Options container not found for videoId=${videoId}, questionId=${questionId}`);
        return;
    }

    // Increment the option index for this question
    if (!optionIndex[videoId]) optionIndex[videoId] = {};
    if (!optionIndex[videoId][questionId]) optionIndex[videoId][questionId] = 0;
    const optionId = optionIndex[videoId][questionId]++;

    // Create the option element
    const optionDiv = document.createElement("div");
    optionDiv.classList.add("form-group", "row", "mb-3"); // Add Bootstrap styling
    optionDiv.innerHTML = `
                <label class="col-lg-2 col-form-label" for="option-${videoId}-${questionId}-${optionId}">
                Option ${optionId + 1}:
                </label>
                <div class="col-lg-4">
                <input type="text"
                id="option-${videoId}-${questionId}-${optionId}"
                name="videos[${videoId}][quizzes][${questionId}][options][${optionId}][option]"
                class="form-control"
                placeholder="Enter option"
                required />
                </div>
                <div class="col-lg-3">
                <div class="form-check">
                <input class="form-check-input"
                type="radio"
                name="videos[${videoId}][quizzes][${questionId}][correct_option]"
                value="${optionId}"
                id="correct-${videoId}-${questionId}-${optionId}" />
                <label class="form-check-label" for="correct-${videoId}-${questionId}-${optionId}">
                Correct
                </label>
                </div>
                </div>
                <div class="col-lg-3">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(${videoId}, ${questionId}, ${optionId})">Remove</button>
                </div>
                `;

    // Append the new option
    optionsContainer.appendChild(optionDiv);
}
function removeOption(videoIdx, questionIdx, optionIdx) {
    document.getElementById(`option-${videoIdx}-${questionIdx}-${optionIdx}`).remove();
}
    </script>
@stop
@stop
