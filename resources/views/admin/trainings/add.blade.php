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
                <form class="form-horizontal" id="frmAdd" method="POST" action="{{ route('training-add') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Training Name -->
                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label" for="trainingName">Training Name<span
                                    class="required">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="name" id="trainingName" class="form-control required"
                                       placeholder="Enter training name"/>
                            </div>
                        </div>
                        <div id="video-section">
                            <!-- Initial Video -->
                            <div class="video-container mb-4 border p-3 rounded">
                                <h4 class="d-flex justify-content-between align-items-center">
                                    <p>Video <span class="video-index">1</span></p>
                                    <button type="button" class="btn btn-danger btn-sm remove-video">Remove</button>
                                </h4>
                                <div class="form-group row mb-5">
                                    <label class="col-lg-3 form-label">Video Title<span class="text-danger required">*</span></label>
                                    <div class="col-lg-6">
                                    <input type="text" name="videos[0][title]" class="form-control" placeholder="Enter video title">
                                    </div>
                                </div>
                                <div class="form-group row mb-5">
                                    <label class="col-lg-3 form-label">Video Description</label>
                                    <div class="col-lg-6">
                                        <textarea name="videos[0][description]" class="form-control" rows="3" placeholder="Enter video description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-5">
                                    <label class="col-lg-3 form-label">Upload Video<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="file" name="videos[0][video]" class="form-control" accept="video/*" />
                                    </div>
                                </div>
                                <div class="form-group row mb-5">
                                    <label class="col-lg-3 form-label">Upload Thumbnail</label>
                                    <div class="col-lg-6">
                                        <input type="file" name="videos[0][thumbnail]" class="form-control" accept="image/*" />
                                    </div>
                                </div>

                                <!-- Quizzes Section -->
                                <div class="quiz-section">
                                    <div class="quiz-container mb-3 border p-2 rounded">
                                        <h5 class="d-flex justify-content-between align-items-center">
                                            <p>Question <span class="quiz-index">1</span></p>
                                            <button type="button" class="btn btn-danger btn-sm remove-quiz">Remove</button>
                                        </h5>
                                        <input type="text" name="videos[0][quizzes][0][question]" class="form-control mb-2" placeholder="Enter question text">

                                        <!-- Options Section -->
                                        <div class="option-section">
                                            <div class="form-group row option-container" data-option-index="0">
                                                <label class="col-lg-2 col-form-label">Option 1:</label>
                                                <div class="col-lg-4">
                                                    <input type="text" name="videos[0][quizzes][0][options][0][option]" class="form-control required" placeholder="Enter option" >
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="radio" name="videos[0][quizzes][0][correct_option]" value="0"> Correct
                                                </div>
                                                <div class="col-lg-3">
                                                    <button type="button" class="btn btn-sm btn-danger remove-option">Remove Option</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary add-option">Add Option</button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary add-quiz">Add Question</button>
                            </div>
                        </div>

                        <!-- Add Video Button -->
                        <button type="button" class="btn btn-primary mt-3" id="add-video">Add Video</button>


                        <!-- Add Video Button -->
{{--                        <button type="button" class="btn btn-success mb-4" onclick="addVideo()">Add Another Video--}}
{{--                        </button>--}}

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

                        // Reindex quizzes inside each video
                        $(this).find(".quiz-container").each(function (qIndex) {
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
        <div class="video-container mb-4 border p-3 rounded">
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
                <div class="quiz-container mb-3 border p-2 rounded">
                    <h5 class="d-flex justify-content-between align-items-center">
                        <p>Question <span class="quiz-index">1</span></p>
                        <button type="button" class="btn btn-danger btn-sm remove-quiz">Remove</button>
                    </h5>
                    <input type="text" name="videos[${newIndex}][quizzes][0][question]" class="form-control mb-2" placeholder="Enter question text">

                    <!-- Options Section -->
                    <div class="option-section">
                        <div class="form-group row option-container" data-option-index="0">
                            <label class="col-lg-2 col-form-label">Option 1:</label>
                            <div class="col-lg-4">
                                <input type="text" name="videos[${newIndex}][quizzes][0][options][0][option]" class="form-control required" placeholder="Enter option">
                            </div>
                            <div class="col-lg-3">
                                <input type="radio" name="videos[${newIndex}][quizzes][0][correct_option]" value="0"> Correct
                            </div>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-sm btn-danger remove-option">Remove Option</button>
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
                    $(this).closest(".video-container").remove();
                    reindex();
                });

                // Add new question (Fixing issue)
                $(document).on("click", ".add-quiz", function () {
                    let videoContainer = $(this).closest(".video-container");
                    let videoIndex = $(".video-container").index(videoContainer); // Ensure correct video index
                    let quizIndex = videoContainer.find(".quiz-container").length;

                    let newQuiz = `
        <div class="quiz-container mb-3 border p-2 rounded">
            <h5 class="d-flex justify-content-between align-items-center">
                <p>Question <span class="quiz-index">${quizIndex + 1}</span></p>
                <button type="button" class="btn btn-danger btn-sm remove-quiz">Remove</button>
            </h5>
            <input type="text" name="videos[${videoIndex}][quizzes][${quizIndex}][question]" class="form-control mb-2" placeholder="Enter question text">

            <!-- Options Section -->
            <div class="option-section">
                <div class="form-group row option-container" data-option-index="0">
                    <label class="col-lg-2 col-form-label">Option 1:</label>
                    <div class="col-lg-4">
                        <input type="text" name="videos[${videoIndex}][quizzes][${quizIndex}][options][0][option]" class="form-control required" placeholder="Enter option">
                    </div>
                    <div class="col-lg-3">
                        <input type="radio" name="videos[${videoIndex}][quizzes][${quizIndex}][correct_option]" value="0"> Correct
                    </div>
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-sm btn-danger remove-option">Remove Option</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary add-option">Add Option</button>
        </div>`;

                    videoContainer.find(".quiz-section").append(newQuiz);
                    reindex();
                });
                // Remove option dynamically
                // Remove option dynamically
                $(document).on("click", ".remove-option", function () {
                    let quizContainer = $(this).closest(".quiz-container");
                    $(this).closest(".option-container").remove();

                    // Reindex all options after removal
                    reindexOptions(quizContainer);
                });

                // Remove question
                $(document).on("click", ".remove-quiz", function () {
                    $(this).closest(".quiz-container").remove();
                    reindex();
                });
                // Add option dynamically
                $(document).on("click", ".add-option", function () {
                    let quizContainer = $(this).closest(".quiz-container");
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
                <button type="button" class="btn btn-sm btn-danger remove-option">Remove Option</button>
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
@stop
