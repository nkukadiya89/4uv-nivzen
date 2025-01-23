@extends('admin.layouts.master')
@section('content')
<div class="d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{$title}}</h5>
                    <!--end::Page Title-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->

        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="p-6 flex-fill">
        <!--begin::Container-->
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form class="form-horizontal" id="frmEdit" action="{{ route('training-edit',$training->id) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                        <div class="col-lg-6">
                            <input id="name" type="text" class="form-control required" name="name" placeholder="Name"
                                value="{{$training->name}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="video">Upload Video</label>
                        <div class="col-lg-6">
                            <input type="file" name="video" id="video" class="form-control">
                            @foreach($videoPaths as $videoPath)
                                <p id="current-video">Current Video: <a href="{{ asset('storage/' . $videoPath) }}" target="_blank">View Video</a></p>
                                @if ($training->videoLessons)
                                @foreach($training->videoLessons as $videoLesson)
                                    <button
                                            type="button"
                                            class="delete-video"
                                            data-training-id="{{ $training->id }}"
                                            data-video-id="{{ $videoLesson->id }}"
                                            data-url="{{ route('training.deleteVideo', ['trainingId' => $training->id, 'videoLessonId' => $videoLesson->id]) }}"
                                    >
                                        Delete Video
                                    </button>
                                @endforeach
                                @endif
                            @endforeach
                            @error('video')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="name">Questions</label>
                        <div class="col-lg-6">
                            <div id="questions-section">
                            @foreach ($training->quizzes as $index => $quiz) <!-- Loop through existing quizzes -->
                                <div class="question-group" id="question-{{ $index }}">
                                    <h4 class="d-flex justify-content-between align-items-center">
                                        Question {{ $index + 1 }}
                                        <button type="button" class="btn btn-sm btn-danger delete-quiz" data-question-id="{{ $index }}" data-quiz-id="{{ $quiz->id }}" data-video-lesson-id="{{ $videoLesson->id }}" data-url="{{ route('training.deleteQuiz', ['id' => $quiz->id]) }}">Remove Question</button>
                                    </h4>
                                    <!-- Access the video lesson associated with this quiz -->
                                    @php
                                        $videoLesson = $training->videoLessons->firstWhere('id', $quiz->video_lesson_id);
                                    @endphp

                                    @if ($videoLesson)
                                        <input type="hidden" name="questions[{{ $index }}][video_lesson_id]" value="{{ $videoLesson->id }}">
                                    @else
                                        <p class="text-danger">No associated video lesson for this quiz.</p>
                                    @endif
                                    <div class="form-group">
                                        <label>Enter Question</label>
                                        <input type="hidden" name="questions[{{ $index }}][quiz_id]" value="{{ $quiz->id }}">
                                        <input type="text" name="questions[{{ $index }}][question]" class="form-control" value="{{ old("questions.$index.question", $quiz->question) }}">
                                        @error("questions.$index.question")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div id="answers-{{ $index }}" class="answers-section">
                                    @foreach ($quiz->options as $key => $option) <!-- Loop through the options for each question -->
                                        <div class="form-group row align-items-center" id="option-{{$index}}-{{$key}}">
                                            <div class="col-lg-2">
                                            <label>Option {{ $key + 1 }}</label>
                                            </div>
                                            <div class="col-lg-4">
                                            <input type="text" name="questions[{{ $index }}][options][{{ $key }}]" class="form-control" value="{{ old("questions.$index.options.$key", $option->option) }}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label>
                                                    <input type="radio" name="questions[{{ $index }}][correct]" value="{{ $key }}" {{ old("questions.$index.correct") == $key ? 'checked' : ($quiz->correct_option == $key ? 'checked' : '') }}> Correct
                                                </label>
                                            </div>
                                            <div class="col-lg-3">
                                                <button type="button" class="btn btn-sm btn-danger delete-answer" data-question-id="{{ $index }}" data-option-id="{{$key}}" data-url="{{ route('training.deleteQuizOption', ['id' => $option->id]) }}">Remove Option</button>
                                            </div>
                                        </div>
                                        @endforeach
                                        @error("questions.$index.correct")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="button" class="btn btn-secondary add-answer" data-question="{{ $index }}">Add Answer Option</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="add-question">Add New Question</button>
                        </div>
                    </div>
                    <!-- Questions Form -->


                </div>
                <!-- /.card-body -->
                <div class="card-footer d-flex justify-content-end">

                    <a href="{{ route('trainings-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>

                </div>
                <!-- /.card-footer -->
            </form>
            <!--end::Form-->
        </div>

        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

@section('custom_js')
<script>
$('#description').summernote({
    height: 200,
});
$(document).ready(function() {

    @if(Session::has('success-message'))
       toastr.info("{{ session('success-message') }}");
    @endif

    let questionCount = {{ count($training->quizzes) }}; // Count the existing questions

    $('#add-question').click(function () {
        questionCount++;
        let questionHtml = `
                <div class="question-group" id="question-${questionCount}">
                    <h4 class="d-flex justify-content-between align-items-center">
        Question ${questionCount}
        <button type="button" class="btn btn-sm btn-danger remove-question" data-question="${questionCount}">Remove Question</button>
    </h4>
                    <div class="form-group">
                        <label>Enter Question</label>
                        <input type="text" name="questions[${questionCount}][question]" class="form-control required" >
                    </div>
                    <div id="answers-${questionCount}" class="answers-section">
                        <div class="form-group row">
                        <div class="col-lg-2">
                            <label>Option 1</label>
                            </div>
                            <div class="col-lg-4">
                            <input type="text" name="questions[${questionCount}][options][0]" class="form-control required" >
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    <input type="radio" name="questions[${questionCount}][correct]" value="0"> Correct
                                </label>
                            </div>
                            <div class="col-lg-3">
                <button type="button" class="btn btn-danger btn-sm delete-option" data-option-id="${questionCount}-0" data-question-id="${questionCount}">
                    Remove Option
                </button>
            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary add-answer" data-question="${questionCount}">Add Answer Option</button>
                </div>
            `;
        $('#questions-section').append(questionHtml);
    });

    $(document).on('click', '.add-answer', function () {
        let questionId = $(this).data('question');
        let answerCount = $(`#answers-${questionId} .form-group`).length;
        let answerHtml = `
                <div  class="form-group row align-items-center" id="option-${questionId}-${answerCount}">
                <div class="col-lg-2">
                    <label>Option ${answerCount + 1}</label>
                 </div>
                 <div class="col-lg-4">
                    <input type="text" name="questions[${questionId}][options][${answerCount}]" class="form-control required" >
                  </div>
                  <div class="col-lg-3">
                    <label>
                        <input type="radio" name="questions[${questionId}][correct]" value="${answerCount}"> Correct
                    </label>
                  </div>
                  <div class="col-lg-3">
                <button type="button" class="btn btn-danger btn-sm delete-option" data-option-id="${questionId}-${answerCount}" data-question-id="${questionId}">
                    Remove Option
                </button>
            </div>
                </div>
            `;
        $(`#answers-${questionId}`).append(answerHtml);
    });

    // Remove Question
    $(document).on('click', '.remove-question', function () {
        $(this).closest('.question-group').remove();
        reindexQuestions();
    });

    $(document).on('click', '.delete-option', function () {
        const optionId = $(this).data('option-id');
        const questionId = $(this).data('question-id');

        if (confirm('Are you sure you want to delete this option?')) {
            $('#option-' + optionId).remove();
            reindexOptions(questionId);
        }
    });
    // Reindex Questions
    function reindexQuestions() {
        $('#questions-section .question-group').each(function (index) {
            let newQuestionIndex = index + 1; // Start numbering at 1
            $(this).attr('id', `question-${newQuestionIndex}`);
            $(this).find('.question-header').text(`Question ${newQuestionIndex}`);

            // Update question input fields and options
            $(this).find('input, label').each(function () {
                let nameAttr = $(this).attr('name');
                if (nameAttr) {
                    // Update question index in name attribute
                    let updatedName = nameAttr.replace(/questions\[\d+\]/, `questions[${newQuestionIndex - 1 }]`);
                    $(this).attr('name', updatedName);
                }

                // Update value of radio button
                let valueAttr = $(this).attr('value');
                if ($(this).is('input[type="radio"]') && valueAttr !== undefined) {
                    let newValue = $(this).closest('.answers-section').find('.form-group').index($(this).closest('.form-group'));
                    $(this).attr('value', newValue);
                }
            });

            // Update data-question attribute
            $(this).find('.add-answer, .remove-question').data('question', newQuestionIndex - 1);
        });

        // Update the global question count
        questionCount = $('#questions-section .question-group').length;
    }

    function reindexOptions(questionId) {
        const answersSection = $('#answers-' + questionId);

        answersSection.children('.form-group').each(function (index) {
            const optionId = `${questionId}-${index}`;
            const optionElement = $(this);

            // Update the wrapper div's ID
            optionElement.attr('id', 'option-' + optionId);

            // Update the label
            optionElement.find('label:first').text(`Option ${index + 1}`);

            // Update the text input's name attribute
            optionElement.find('input[type="text"]').attr('name', `questions[${questionId}][options][${index}]`);

            // Update the radio button's name and value attributes
            const radioButton = optionElement.find('input[type="radio"]');
            radioButton.attr('name', `questions[${questionId}][correct]`);
            radioButton.attr('value', index);
        });
    }
});
$(document).on('click', '.delete-video', function () {
    if (confirm('Are you sure you want to delete this video and its associated questions?')) {
        const url = $(this).data('url');
        const videoId = $(this).data('video-id');
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    alert('Video and associated questions deleted successfully!');
                    // Optionally, remove the video and its related questions from the DOM
                    $('#current-video').remove();
                    $('.question-group').remove();
                } else {
                    alert('Failed to delete video and questions.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the video and questions.');
            }
        });
    }
});
// Delete Question (Optional)
$(document).on('click', '.delete-quiz', function () {
    const $button = $(this);
    const questionId = $button.data('question-id');
    const quizId = $(this).data('quiz-id');
    const videoLessonId = $(this).data('video-lesson-id');
    const url = $(this).data('url');

    if (confirm('Are you sure you want to delete this question?')) {
        $.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    alert('Question deleted successfully!');

                    // Remove question from DOM
                    //questionId.remove();
                    $('#question-' + questionId).remove();
                    //$(this).closest('.question-group').remove();
                } else {
                    alert('Failed to delete question.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the question.');
            }
        });
    }
});
$(document).on('click', '.delete-answer', function () {
    const optionId = $(this).data('option-id');
    const questionId = $(this).data('question-id');
    const url = $(this).data('url');
    // Example AJAX call to delete the option from the server
    $.ajax({
        url: url,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                alert('Option deleted successfully!');
                // Reindex remaining options under the question
                $(`#option-${questionId}-${optionId}`).remove();
                reIndexAnswerOptions(questionId);
            } else {
                alert('Failed to delete the option.');
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred while deleting the option.');
        }
    });
});
function reIndexAnswerOptions(questionId) {
    const options = $(`#answers-${questionId} .form-group.row`);

    options.each(function (index) {
        const optionGroup = $(this);
        const optionLabel = optionGroup.find('label').first();  // Label for the option (e.g., "Option 1")
        const optionInput = optionGroup.find('input[type="text"]');  // Input field for the option
        const radioInput = optionGroup.find('input[type="radio"]');  // Radio button for the correct answer

        // Reindex the option label
        optionLabel.text(`Option ${index + 1}`);

        // Update the input field name
        optionInput.attr('name', `questions[${questionId}][options][${index}]`);

        // Update the radio button values and name
        radioInput.attr('value', index);
        radioInput.attr('name', `questions[${questionId}][correct]`);
    });

}
</script>
@stop

@stop