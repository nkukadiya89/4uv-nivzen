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
                            <label class="col-lg-3 col-form-label" for="trainingName">Training Name<span class="required">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="name" id="trainingName" class="form-control required" placeholder="Enter training name" value="{{ $training->name ?? '' }}" />
                            </div>
                        </div>
                        <div id="video-section">
                            <!-- Existing Videos -->
                            @if(isset($training->videoLessons) && count($training->videoLessons) > 0)
                                @foreach($training->videoLessons as $videoIndex => $video)
                                    <div class="video-container mb-4 border p-3 rounded" data-video-index="{{ $videoIndex }}">
                                        <h4 class="d-flex justify-content-between align-items-center">
                                            <p>Video <span class="video-index">{{ $videoIndex + 1 }}</span></p>
                                            <button type="button" class="btn btn-danger btn-sm remove-video" data-training-id="{{$training->id}}" data-video-index="{{$videoIndex}}">Remove</button>
                                        </h4>

                                        <input type="hidden" name="videos[{{ $videoIndex }}][id]" value="{{ $video->id }}">
                                        <div class="form-group row">
                                            <label class="col-lg-3 form-label">Video Title<span class="required">*</span></label>
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
                                            <label class="col-lg-3 form-label">Upload Video<span class="required">*</span></label>
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
                                        <div class="quiz-section">


{{--                                                <h5 class="d-flex justify-content-between align-items-center">Questions--}}
{{--                                                    <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion({{ $videoIndex }})">Add Question</button>--}}
{{--                                                </h5>--}}

                                                @foreach($video->quizzes as $quizIndex => $quiz)
                                                    <div id="question-container-{{ $videoIndex }}-{{ $quizIndex }}" class="question-container mb-4 border p-3 rounded" data-quiz-index="{{ $quizIndex }}">
                                                        <div class="form-group row">
                                                            <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][id]" value="{{ $quiz->id }}">
                                                            <label class="col-lg-2 col-form-label">
                                                                <p>Question <span class="quiz-index">{{ $quizIndex + 1 }}</span></p><span class="required">*</span>
                                                            </label>
                                                            <div class="col-lg-7">
                                                                <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][question]" class="form-control required" placeholder="Enter question text" value="{{ $quiz->question }}"  />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <button type="button" class="btn btn-danger btn-sm remove-quiz" data-video-index="{{$videoIndex}}" data-quiz-index="{{$quizIndex}}">Remove</button>
                                                            </div>
                                                        </div>

                                                        <!-- Options Section -->
                                                        <div class="option-section">
                                                        @foreach($quiz->options as $optionIndex => $option)

                                                            <div class="form-group row option-container justify-content-between align-items-center" data-option-index="{{ $optionIndex }}">
                                                                <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                                                <label class="col-lg-2 col-form-label">Option {{ $optionIndex + 1 }}:</label>
                                                                <div class="col-lg-4">
                                                                    <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][option]" class="form-control required" placeholder="Enter option" value="{{ $option->option }}" />
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <input type="radio" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][correct_option]" value="{{ $optionIndex }}" {{ $option->is_correct ? 'checked' : '' }}> Correct
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-option" data-video-index="{{ $videoIndex }}" data-quiz-index="{{ $quizIndex }}" data-option-index="{{$optionIndex}}">Remove</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                         </div>
                                                        <button type="button" class="btn btn-sm btn-primary add-option" data-video-index="{{$videoIndex}}" data-quiz-index="{{$quizIndex}}">Add Option</button>
                                                    </div>
                                                @endforeach

                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary add-quiz">Add Question</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- Add Video Button -->
                        <button type="button" class="btn btn-primary mt-3" id="add-video">Add Video</button>
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
            let videoIndex = $("#video-section .video-container").length;

            // Function to update indexes of videos and questions
            function reindex() {
                $(".video-container").each(function (vIndex) {
                    $(this).find(".video-index").text(vIndex + 1);
                    $(this).find("[name^='videos']").each(function () {
                        let nameAttr = $(this).attr("name");
                        if (nameAttr) {
                            let updatedName = nameAttr.replace(/\[videos\]\[\d+\]/, `[videos][${vIndex}]`);
                            $(this).attr("name", updatedName);
                        }
                    });
                    console.log($(this).find(".question-container"));
                    // Reindex quizzes inside each video
                    $(this).find(".question-container").each(function (qIndex) {
                        $(this).find(".quiz-index").text(qIndex + 1);
                        $(this).find("[name^='videos']").each(function () {
                            let nameAttr = $(this).attr("name");
                            if (nameAttr) {
                                let updatedName = nameAttr.replace(/\[quizzes\]\[\d+\]/, `[quizzes][${qIndex}]`);
                                $(this).attr("name", updatedName);
                            }
                        });
                    });
                });
            }

            // Add new video
            $("#add-video").click(function () {
                let newIndex = videoIndex;
                let newVideo = `
        <div class="video-container mb-4 border p-3 rounded" data-video-index="${newIndex}">
            <h4 class="d-flex justify-content-between align-items-center">
                <p>Video <span class="video-index">${newIndex + 1}</span></p>
                <button type="button" class="btn btn-danger btn-sm remove-video">Remove</button>
            </h4>
            <div class="form-group row mb-5">
                <label class="col-lg-3 form-label">Video Title<span class="required">*</span></label>
                <div class="col-lg-6"><input type="text" name="videos[${newIndex}][title]" class="form-control" placeholder="Enter video title"></div>
            </div>
            <div class="form-group row mb-5">
                <label class="col-lg-3 form-label">Video Description</label>
                <div class="col-lg-6"><textarea name="videos[${newIndex}][description]" class="form-control" rows="3" placeholder="Enter video description"></textarea></div>
            </div>
            <div class="form-group row mb-5">
                <label class="col-lg-3 form-label">Upload Video<span class="required">*</span></label>
                <div class="col-lg-6"><input type="file" name="videos[${newIndex}][video]" class="form-control" accept="video/*" /></div>
            </div>
            <div class="form-group row mb-5">
                <label class="col-lg-3 form-label">Upload Thumbnail</label>
                <div class="col-lg-6"><input type="file" name="videos[${newIndex}][thumbnail]" class="form-control" accept="image/*" /></div>
            </div>

            <!-- Quiz Section -->
            <div class="quiz-section">
                <div id="question-container-${newIndex}-0" class="question-container mb-3 border p-2 rounded" data-quiz-index="0">
                    <div class="form-group row">
                         <label class="col-lg-2 col-form-label"><p>Question <span class="quiz-index">1</span></p></label>
                         <div class="col-lg-7"><input type="text" name="videos[${newIndex}][quizzes][0][question]" class="form-control mb-5" placeholder="Enter question text"></div>
                         <div class="col-lg-3">
                              <button type="button" class="btn btn-danger btn-sm remove-quiz" data-video-index="${newIndex}" data-quiz-index="0">Remove</button>
                          </div>
                    </div>


                    <!-- Options Section -->
                    <div class="option-section">
                        <div class="form-group row option-container justify-content-between align-items-center" data-option-index="0">
                            <label class="col-lg-2 col-form-label">Option 1:</label>
                            <div class="col-lg-4">
                                <input type="text" name="videos[${newIndex}][quizzes][0][options][0][option]" class="form-control required" placeholder="Enter option">
                            </div>
                            <div class="col-lg-3">
                                <input type="radio" name="videos[${newIndex}][quizzes][0][correct_option]" value="0"> Correct
                            </div>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-sm btn-danger remove-option" data-video-index="${newIndex}" data-quiz-index="0" data-option-index="0">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary add-option">Add Option</button>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-secondary add-quiz">Add Question</button>
        </div>
        `;

                $("#video-section").append(newVideo);
                videoIndex++;
                reindex();
            });

            // Remove video
            $(document).on("click", ".remove-video", function () {
                var trainingId = $(this).attr("data-training-id"); // OR $(this).data("training-id");
                var videoIndex = $(this).attr("data-video-index"); // OR $(this).data("video-index");
                var videoContainer = $(this).closest(".video-container");
                var videoIdInput = videoContainer.find("input[type='hidden'][name^='videos'][name$='[id]']");
                if (trainingId && videoIdInput.length) {
                    var videoId = videoIdInput.val();
                    console.log("Video ID:", videoId);

                    // Perform any additional checks if needed
                    if (videoId) {
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
                                    url: `/backend/training/${trainingId}/video/${videoId}`,
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
                                        videoContainer.remove();
                                        reindex();
                                    },
                                    error: function (xhr) {
                                        console.error("Error deleting video:", xhr.responseText);
                                        swal("Error!", "Error deleting video. Please try again.", "error");
                                    }
                                });

                            }
                        });
                    }
                } else {
                    console.log('new fresh video not id');
                    videoContainer.remove();
                    reindex();
                }


                // $(this).closest(".video-container").remove();
                // reindex();
            });

            // Add new question (Fixing issue)
            $(document).on("click", ".add-quiz", function () {
                let videoContainer = $(this).closest(".video-container");
                let videoIndex = $(".video-container").index(videoContainer); // Ensure correct video index
                let quizIndex = videoContainer.find(".question-container").length;

                let newQuiz = `
        <div id="question-container-${videoIndex}-${quizIndex}" class="question-container mb-3 border p-2 rounded">
             <div class="form-group row">
                         <label class="col-lg-2 col-form-label"><p>Question <span class="quiz-index">${quizIndex + 1}</span></p></label>
                         <div class="col-lg-7"><input type="text" name="videos[${videoIndex}][quizzes][${quizIndex}][question]" class="form-control mb-5" placeholder="Enter question text"></div>
                         <div class="col-lg-3">
                             <button type="button" class="btn btn-danger btn-sm remove-quiz" data-video-index="${videoIndex}" data-quiz-index="${quizIndex}">Remove</button>
                         </div>
             </div>
            <!-- Options Section -->
            <div class="option-section">
                <div class="form-group row option-container justify-content-between align-items-center" data-option-index="0">
                    <label class="col-lg-2 col-form-label">Option 1:</label>
                    <div class="col-lg-4">
                        <input type="text" name="videos[${videoIndex}][quizzes][${quizIndex}][options][0][option]" class="form-control required" placeholder="Enter option">
                    </div>
                    <div class="col-lg-3">
                        <input type="radio" name="videos[${videoIndex}][quizzes][${quizIndex}][correct_option]" value="0"> Correct
                    </div>
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-sm btn-danger remove-option" data-video-index="${videoIndex}" data-quiz-index="${quizIndex}" data-option-index="0">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary add-option">Add Option</button>
        </div>`;

                videoContainer.find(".quiz-section").append(newQuiz);
                reindex();
            });
            // Remove option dynamically
            $(document).on("click", ".remove-option", function () {
                // let quizContainer = $(this).closest(".question-container");
                // $(this).closest(".option-container").remove();
                //
                // // Reindex all options after removal
                // reindexOptions(quizContainer);

                let optionContainer = $(this).closest(".option-container");
                let optionSection = optionContainer.closest(".option-section"); // Find the parent section containing all options
                let optionIndex = optionContainer.data("option-index");
                let videoIndex = $(this).data("video-index");
                let questionIndex = $(this).data("quiz-index");

                if (optionContainer.length === 0) {
                    console.error("Error: Could not find option container.");
                    return;
                }

                // Find the hidden input inside the optionContainer
                let optionInput = optionContainer.find(`input[name="videos[${videoIndex}][quizzes][${questionIndex}][options][${optionIndex}][id]"]`);
                let optionId = optionInput.val(); // Get option ID from the hidden input

                if (optionId) {
                    swal.fire({
                        title: 'Are you sure you want to delete this answer?',
                        text: '',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: `/backend/delete-quiz-option/${optionId}`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function () {
                                    swal.fire('Deleted!', 'Your record has been deleted.', 'success');
                                    optionContainer.remove(); // Remove from DOM
                                    reindexOptionsIds(optionSection, videoIndex, questionIndex); // Reindex remaining options
                                },
                                error: function () {
                                    swal.fire("Error!", "Error deleting answer. Please try again.", "error");
                                }
                            });
                        }
                    });
                } else {
                    optionContainer.remove(); // Remove from DOM if not saved in the database
                    reindexOptionsIds(optionSection, videoIndex, questionIndex); // Reindex remaining options
                }
            });
// Function to reindex options dynamically
            function reindexOptionsIds(optionSection, videoIndex, questionIndex) {
                optionSection.find(".option-container").each(function (index) {
                    $(this).attr("data-option-index", index);
                    $(this).find("label").text(`Option ${index + 1}:`);

                    // Update input field names with correct indexes
                    $(this).find("input[type='hidden']").attr("name", `videos[${videoIndex}][quizzes][${questionIndex}][options][${index}][id]`);
                    $(this).find("input[type='text']").attr("name", `videos[${videoIndex}][quizzes][${questionIndex}][options][${index}][option]`);
                    $(this).find("input[type='radio']").attr("name", `videos[${videoIndex}][quizzes][${questionIndex}][correct_option]`).val(index);
                });
            }
            // Remove question
            $(document).on("click", ".remove-quiz", function () {
                let videoIndex = $(this).data("video-index");
                let questionIndex = $(this).data("quiz-index");
                let questionContainer = $(`#question-container-${videoIndex}-${questionIndex}`);
                 console.log("questionContainer", questionContainer);
                if (questionContainer.length === 0) {
                    console.error("Question container not found!");
                    //questionContainer.remove();
                    $(this).closest(".question-container").remove();
                    reindex();
                    //return;
                }

                // Get Question ID
                let questionIdInput = questionContainer.find(`input[name="videos[${videoIndex}][quizzes][${questionIndex}][id]"]`);
                let questionId = questionIdInput.length ? questionIdInput.val() : null;
                 console.log("questionId", questionId);
                if (questionId) {
                    Swal.fire({
                        title: "Are you sure you want to delete this question and all its options?",
                        text: "",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "Cancel",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/backend/delete-quiz/${questionId}`,
                                type: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                },
                                success: function (response) {
                                    Swal.fire(
                                        "Deleted!",
                                        "Your record has been deleted.",
                                        "success"
                                    );
                                    questionContainer.remove(); // Remove from DOM
                                    reindex();
                                },
                                error: function (xhr) {
                                    console.error("Error deleting question:", xhr.responseText);
                                    Swal.fire("Error!", "Error deleting question. Please try again.", "error");
                                }
                            });
                        }
                    });
               } else {
                  questionContainer.remove();
                    reindex();
               }
                // $(this).closest(".quiz-container").remove();
                // reindex();
            });
            // Add option dynamically
            $(document).on("click", ".add-option", function () {
                let quizContainer = $(this).closest(".question-container");
                let videoContainer = quizContainer.closest(".video-container");

                // Store videoIndex and quizIndex for proper naming
                quizContainer.data("video-index", videoContainer.index());
                quizContainer.data("quiz-index", quizContainer.index());

                let optionSection = quizContainer.find(".option-section");
                let optionIndex = optionSection.find(".option-container").length;

                let newOption = `
        <div class="form-group row option-container">
            <label class="col-lg-2 col-form-label">Option ${optionIndex + 1}:</label>
            <div class="col-lg-4">
                <input type="text" class="form-control required" placeholder="Enter option">
            </div>
            <div class="col-lg-3">
                <input type="radio"> Correct
            </div>
            <div class="col-lg-3">
                <button type="button" class="btn btn-sm btn-danger remove-option" data-video-index="${videoContainer.index()}" data-quiz-index="${quizContainer.index()}" data-option-index="${optionIndex + 1}">Remove</button>
            </div>
        </div>`;

                optionSection.append(newOption);

                // Reindex all options
                reindexOptions(quizContainer);
            });
            // Function to reindex options
            function reindexOptions(quizContainer) {
                quizContainer.find(".option-container").each(function (index) {
                    $(this).attr("data-option-index", index);
                    $(this).find("label").text(`Option ${index + 1}:`);
                    $(this).find("input[type='text']").attr("name", `videos[${quizContainer.data('video-index')}][quizzes][${quizContainer.data('quiz-index')}][options][${index}][option]`);
                    $(this).find("input[type='radio']").attr("name", `videos[${quizContainer.data('video-index')}][quizzes][${quizContainer.data('quiz-index')}][correct_option]`).val(index);
                });
            }
        });

    </script>
@stop
