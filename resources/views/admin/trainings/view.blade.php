@extends('admin.layouts.master')
@section('content')
<div class=" d-flex flex-column flex-column-fluid" id="kt_content">
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
        <div class="card card-custom gutter-b">
            <div class="card-header align-content-center">
                <div class="card-title">
                </div>
                <div class="p-2">
                    <!--begin::Button-->
                    <a href="{{ route('trainings-manage') }}" class="btn btn-primary">
                        Back</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 ">
                        <div class="form-group">
                            <label class="customlbl">Name</label>
                            <div>

                                {{$training->name ?? ''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="customlbl">Video</label>
                            <div class="videoswrp">
                                @if(!empty($videoPaths))
                                @foreach($videoPaths as $video)
                                <div class="video-item">
                                    <!-- Create a video thumbnail preview -->
                                    <video width="300" height="200" controls>
                                        <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                @endforeach
                                @else
                                <p>No videos uploaded for this training program.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 ">
                        <div class="form-group">
                            <label class="customlbl">Total Questions</label>
                            <div>

                                {{$totalQuestions ?? ''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 ">
                        <div id="quiz-container">
                            <!-- Quiz questions will be dynamically loaded here -->
                        </div>
                        <button id="submit-quiz" class="btn btn-primary" style="display: none;">Submit Answers</button>
                    </div>
                </div>

            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@section('custom_js')
<script>
$(document).ready(function() {

    @if(Session::has('success-message'))
      toastr.info("{{ session('success-message') }}");
    @endif

    let quizzes = [];
    var video = document.getElementsByTagName('video')[0];

    video.onended = function(e) {
        console.log("finish");

        var url = '{{config('constants.ADMIN_URL ')}}trainings/store-training-activity';
        var videoLessonId = {{ $training->videoLessons->first()->id ?? 'null' }};
        var completedAt = new Date().toISOString().slice(0, 19).replace("T", " ");
        $.ajax({
            url: '/backend/trainings/store-training-activity', // The route to store the training activity
            method: 'POST',
            data: {
                user_id: {{ Auth::user()->id }}, // Get the logged-in user ID
                training_id: {{ $training->id }}, // Current training ID
                video_lesson_id: videoLessonId, // Current video lesson ID
                completed_at: completedAt, // The completion timestamp
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Training activity recorded!');
                    // Optionally, show the quiz questions and options here
                    loadQuizQuestions(response.video_lesson_id); // Load the questions after video completion
                } else {
                    alert('Failed to record the activity.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while recording the activity.');
            }
        });
    };


    // Function to load quiz questions and options
    function loadQuizQuestions(videoLessonId) {
        var videoLessonId = {{ $training->videoLessons->first()->id ?? 'null' }};
        $.ajax({
            url: '/backend/trainings/get-quiz-questions', // Route to fetch quiz questions based on video lesson ID
            method: 'GET',
            data: { video_lesson_id: videoLessonId },
            success: function(response) {
                if (response.success) {
                    // Show the quiz questions and options
                    displayQuiz(response.questions);
                    // Attach event listener to radio buttons
                    $('input[type="radio"]').on('change', checkAllAnswers);

                    // Initially disable the submit button
                    $('#submit-quiz').prop('disabled', true).show();
                } else {
                    alert('Failed to load quiz questions.');
                    $('#submit-quiz').hide();
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while loading quiz questions.');
                $('#submit-quiz').hide();
            }
        });
    }

// Function to display quiz questions and options
    function displayQuiz(questions) {
        quizzes = questions; // Store quizzes for submission
        let html = '';
        questions.forEach((quiz, index) => {
            html += `
                        <div class="quiz">
                            <p>${index + 1}. ${quiz.question}</p>
                            ${quiz.options
            .map(
                option => `
                                <label>
                                    <input type="radio" name="quiz_${quiz.id}" value="${option.id}" />
                                    ${option.option}
                                </label>
                            `
            )
            .join('')}
                        </div>`;
         });
        document.getElementById('quiz-container').innerHTML = html;
    }

    // Function to check if all questions have a selected answer
    function checkAllAnswers() {
        const totalQuestions = $('.quiz-question').length; // Total number of questions
        const answeredQuestions = $('.quiz-question input[type="radio"]:checked').length; // Total answered questions

        // Enable submit button only if all questions are answered
        if (answeredQuestions === totalQuestions) {
            $('#submit-quiz').prop('disabled', false); // Enable button
        } else {
            $('#submit-quiz').prop('disabled', true); // Disable button
        }
    }
    $('#submit-quiz').on('click', function () {
        const answers = [];

        // Iterate over quizzes and collect selected options
        quizzes.forEach(function (quiz) {
            const selectedOption = $(`input[name="quiz_${quiz.id}"]:checked`);
            if (selectedOption.length) {
                answers.push({
                    quiz_id: quiz.id,
                    option_id: selectedOption.val(),
                });
            }
        });

        if (answers.length < quizzes.length) {
            alert('Please answer all questions.');
            return;
        }

        // Submit the quiz answers via AJAX
        $.ajax({
            url: '/backend/trainings/submit-quiz-answers',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            contentType: 'application/json',
            data: JSON.stringify({
                user_id: {{ Auth::user()->id }},
                video_lesson_id: {{ $training->videoLessons->first()->id ?? 'null' }},
                answers: answers,
            }),
            success: function (data) {
                if (data.success) {
                    alert('Quiz submitted successfully!');
                } else {
                    alert(data.message || 'Failed to submit quiz.');
                }
            },
            error: function (xhr) {
                console.error('Error submitting quiz:', xhr.responseText);
            },
        });
    });
});

</script>
@stop
@stop