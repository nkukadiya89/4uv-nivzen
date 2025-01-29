@extends('admin.layouts.master')

@section('content')
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <!-- Subheader -->
        <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ $title }}</h5>
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
                <form class="form-horizontal" id="frmEdit" method="POST" action="{{ route('training-edit', $training->id ?? null) }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($training))
                        @method('PUT')
                    @endif
                    <div class="card-body">
                        <!-- Training Name -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="trainingName">Training Name</label>
                            <div class="col-lg-6">
                                <input type="text" name="name" id="trainingName" class="form-control required" placeholder="Enter training name" value="{{ $training->name ?? '' }}" />
                            </div>
                        </div>
                        <div id="video-section">
                            <!-- Existing Videos -->
                            @if(isset($training->videoLessons) && count($training->videoLessons) > 0)
                                @foreach($training->videoLessons as $videoIndex => $video)
                                    <div class="video-container mb-4 border p-3 rounded" data-video-index="{{ $videoIndex }}">
                                        <h4 class="d-flex justify-content-between align-items-center">Video {{ $videoIndex + 1 }}
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeVideo({{$training->id}}, {{ $videoIndex }})">Remove Video</button></h4>
                                        <input type="hidden" name="videos[{{ $videoIndex }}][id]" value="{{ $video->id }}">
                                        <div class="form-group row">
                                            <label class="col-lg-3 form-label">Video Title</label>
                                            <div class="col-lg-6">
                                                <input type="text" name="videos[{{ $videoIndex }}][title]" class="form-control required" placeholder="Enter video title" value="{{ $video->title }}" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 form-label">Video Description</label>
                                            <div class="col-lg-6">
                                                <textarea name="videos[{{ $videoIndex }}][description]" class="form-control" rows="3" placeholder="Enter video description">{{ $video->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 form-label">Upload Video</label>
                                            <div class="col-lg-6">
                                                <input type="file" name="videos[{{ $videoIndex }}][video]" class="form-control" accept="video/*" />
                                                <p id="current-video">Current Video: <a href="{{ asset('storage/' . $video->video_url) }}" target="_blank">View Video</a></p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 form-label">Upload Thumbnail</label>
                                            <div class="col-lg-6">
                                                <input type="file" name="videos[{{ $videoIndex }}][thumbnail]" class="form-control" accept="image/*" />
                                                @if($video->thumbnail_url)
                                                    <div class="mt-2">
                                                        <img id="current-thumbnail" src="{{ asset('storage/' . $video->thumbnail_url) }}" alt="Thumbnail" class="img-thumbnail" width="100"></p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mcq-section mb-4">
                                            <h5 class="d-flex justify-content-between align-items-center">Questions
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion({{ $videoIndex }})">Add Question</button>
                                            </h5>

                                            @foreach($video->quizzes as $quizIndex => $quiz)
                                                <div id="question-container-{{ $videoIndex }}-{{ $quizIndex }}" class="question-container mb-4 border p-3 rounded" data-question-index="{{ $quizIndex }}">
                                                    <div class="form-group row">
                                                        <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][id]" value="{{ $quiz->id }}">
                                                        <label class="col-lg-2 col-form-label">Question: {{$quizIndex + 1}}</label>
                                                        <div class="col-lg-7">
                                                            <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][question]" class="form-control" placeholder="Enter question" value="{{ $quiz->question }}" required />
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion({{ $videoIndex }}, {{ $quizIndex }})">Remove Question</button>
                                                        </div>
                                                    </div>
                                                    @foreach($quiz->options as $optionIndex => $option)

                                                        <div class="form-group row option-container" data-option-index="{{ $optionIndex }}">
                                                            <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                                            <label class="col-lg-2 col-form-label">Option {{ $optionIndex + 1 }}:</label>
                                                            <div class="col-lg-4">
                                                                <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][option]" class="form-control" placeholder="Enter option" value="{{ $option->option }}" required />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="radio" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][correct_option]" value="{{ $optionIndex }}" {{ $option->is_correct ? 'checked' : '' }}> Correct
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeOption({{ $videoIndex }}, {{ $quizIndex }}, {{$optionIndex}})">Remove Option</button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <button type="button" class="btn btn-sm btn-secondary" onclick="addOption({{ $videoIndex }}, {{ $quizIndex }})">Add Option</button>

                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                @endforeach
                            @endif
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
@endsection

@section('custom_js')
    <script>
        $(document).ready(function () {

        });
        let videoCount = document.querySelectorAll('.video-container').length || 0;

        // Add a new video
        function addVideo() {
            const videoSection = document.getElementById('video-section');
            const newVideoIndex = videoCount++;

            const videoTemplate = `
        <div class="video-container mb-4 border p-3 rounded" data-video-index="${newVideoIndex}">
            <h4 class="d-flex justify-content-between align-items-center">Video ${newVideoIndex + 1}
            <button type="button" class="btn btn-sm btn-danger mt-3" onclick="removeVideo(null, ${newVideoIndex})">Remove Video</button>
            </h4>
            <div class="form-group row">
                <label class="col-lg-3 form-label">Video Title</label>
                <div class="col-lg-6">
                    <input type="text" name="videos[${newVideoIndex}][title]" class="form-control required" placeholder="Enter video title" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 form-label">Video Description</label>
                <div class="col-lg-6">
                    <textarea name="videos[${newVideoIndex}][description]" class="form-control" rows="3" placeholder="Enter video description"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 form-label">Upload Video</label>
                <div class="col-lg-6">
                    <input type="file" name="videos[${newVideoIndex}][video]" class="form-control" accept="video/*" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 form-label">Upload Thumbnail</label>
                <div class="col-lg-6">
                    <input type="file" name="videos[${newVideoIndex}][thumbnail]" class="form-control" accept="image/*" />
                </div>
            </div>
            <div class="mcq-section mb-4">
                <h5 class="d-flex justify-content-between align-items-center">Questions
                <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion(${newVideoIndex})">Add Question</button>
                </h5>
            </div>
        </div>
    `;

            videoSection.insertAdjacentHTML('beforeend', videoTemplate);
        }

        // Remove a video
        function removeVideo(trainingId, videoIndex) {
            const videoContainer = document.querySelector(`.video-container[data-video-index="${videoIndex}"]`);

            if (!videoContainer) {
                console.error("Video container not found!");
                return;
            }

            // Get Video ID from Hidden Input Field
            const videoIdInput = videoContainer.querySelector(`input[name="videos[${videoIndex}][id]"]`);
            const videoLessonId = videoIdInput ? videoIdInput.value : null;

            if (videoLessonId) {
                swal.fire({
                    title: 'Are you sure You want to delete this record?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: `/backend/training/${trainingId}/video/${videoLessonId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                console.log(response.message);
                                swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                );
                                videoContainer.remove(); // Remove from DOM
                            },
                            error: function (xhr) {
                                console.error("Error deleting video:", xhr.responseText);
                                swal("Error!", "Error deleting video. Please try again.", "error");
                            }
                        });

                    }
                });
            } else {
                videoContainer.remove();
            }
        }

        // Add a new question to a specific video
        function addQuestion(videoIndex) {
            const videoContainer = document.querySelector(`.video-container[data-video-index="${videoIndex}"]`);
            const mcqSection = videoContainer.querySelector('.mcq-section');
            const questionCount = videoContainer.querySelectorAll('.question-container').length || 0;

            const questionTemplate = `
        <div id="question-container-${videoIndex}-${questionCount}" class="question-container mb-4 border p-3 rounded" data-question-index="${questionCount}">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Question: ${questionCount + 1}</label>
                <div class="col-lg-7">
                    <input type="text" name="videos[${videoIndex}][quizzes][${questionCount}][question]" class="form-control required" placeholder="Enter question"  />
                </div>
                <div class="col-lg-3">
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeQuestion(${videoIndex}, ${questionCount})">Remove Question</button>
                </div>
            </div>
            <div class="option-section">
                <div class="form-group row option-container" data-option-index="0">
                    <label class="col-lg-2 col-form-label">Option 1:</label>
                    <div class="col-lg-4">
                        <input type="text" name="videos[${videoIndex}][quizzes][${questionCount}][options][0][option]" class="form-control required" placeholder="Enter option"  />
                    </div>
                    <div class="col-lg-3">
                        <input type="radio" name="videos[${videoIndex}][quizzes][${questionCount}][correct_option]" value="0"> Correct
                    </div>
                    <div class="col-lg-3">
              <button type="button" class="btn btn-sm btn-danger" onclick="removeOption( ${videoIndex}, ${questionCount}, 0)">Remove Option</button>
            </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addOption(${videoIndex}, ${questionCount})">Add Option</button>

        </div>
    `;

            mcqSection.insertAdjacentHTML('beforeend', questionTemplate);
        }

        // Remove a question from a specific video
        function removeQuestion(videoIndex, questionIndex) {
            const questionContainer = document.getElementById(`question-container-${videoIndex}-${questionIndex}`);

            if (!questionContainer) {
                console.error("Question container not found!");
                return;
            }

            // Get Question ID
            const questionIdInput = questionContainer.querySelector(`input[name="videos[${videoIndex}][quizzes][${questionIndex}][id]"]`);
            const questionId = questionIdInput ? questionIdInput.value : null;

            if (questionId) {
                swal.fire({
                    title: 'Are you sure you want to delete this question and all its options?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: `/backend/delete-quiz/${questionId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                );
                                questionContainer.remove(); // Remove from DOM
                            },
                            error: function (xhr) {
                                console.error("Error deleting question:", xhr.responseText);
                                swal("Error!", "Error deleting question. Please try again.", "error");
                            }
                        });

                    }
                });
            } else {
                questionContainer.remove();
            }
        }

        // Add a new option to a specific question
        function addOption(videoIndex, questionIndex) {


            const questionContainer = document.getElementById(`question-container-${videoIndex}-${questionIndex}`);

            // Check if the question container is found
            if (!questionContainer) {
                console.error("Question container not found!");
                return;
            }

            // Find the option section within the question container
            let optionSection = questionContainer.querySelector('.option-section');

            // If option section is not found, create and append it
            if (!optionSection) {
                optionSection = document.createElement('div');
                optionSection.classList.add('option-section');
                questionContainer.appendChild(optionSection);
            }

            const optionCount = questionContainer.querySelectorAll('.option-container').length || 0;

            const optionTemplate = `
        <div class="form-group row option-container" data-option-index="${optionCount}">
            <label class="col-lg-2 col-form-label">Option ${optionCount + 1}:</label>
            <div class="col-lg-4">
                <input type="text" name="videos[${videoIndex}][quizzes][${questionIndex}][options][${optionCount}][option]" class="form-control" placeholder="Enter option" required />
            </div>
            <div class="col-lg-3">
                <input type="radio" name="videos[${videoIndex}][quizzes][${questionIndex}][correct_option]" value="${optionCount}"> Correct
            </div>
            <div class="col-lg-3">
              <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(${videoIndex}, ${questionIndex}, ${optionCount})">Remove Option</button>
            </div>
        </div>
    `;

            // Insert the new option into the option section
            optionSection.insertAdjacentHTML('beforeend', optionTemplate);
        }

        // Remove an option (optional - not mandatory)
        function removeOption(videoIndex, questionIndex, optionIndex) {
            let optionContainer = $(`.option-container[data-option-index="${optionIndex}"]`);

            if (optionContainer.length === 0) {
                console.error("Error: Could not find option container.");
                return;
            }

            // Find the hidden input inside the optionContainer
            let optionInput = optionContainer.find(`input[name^="videos[${videoIndex}][quizzes][${questionIndex}][options][${optionIndex}][id]"]`);

            if (optionInput.length === 0) {
                console.warn("Hidden input not found for option ID.");
            }

            let optionId = optionInput.val(); // Get option ID from the hidden input

            if (optionId) {
                swal.fire({
                    title: 'Are you sure you want to delete this answer?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: `/backend/delete-quiz-option/${optionId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                );
                                optionContainer.remove(); // Remove from DOM
                            },
                            error: function (xhr) {
                                swal("Error!", "Error deleting answer. Please try again.", "error");
                            }
                        });

                    }
                });
            } else {
                optionContainer.remove(); // Remove from DOM if not saved in the database
            }
        }
    </script>

@stop
