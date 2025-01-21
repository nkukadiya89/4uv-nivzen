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
                    <div class="form-group">
                        <label for="video">Upload Video</label>
                        <input type="file" name="video" id="video" class="form-control">
                        @foreach($videoPaths as $videoPath)
                            <p>Current Video: <a href="{{ asset('storage/' . $videoPath) }}" target="_blank">View Video</a></p>
                        @endforeach
                        @error('video')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Questions Form -->
                    <div id="questions-section">
                    @foreach ($training->quizzes as $index => $quiz) <!-- Loop through existing quizzes -->
                        <div class="question-group" id="question-{{ $index }}">
                            <h4>Question {{ $index + 1 }}</h4>
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
                                <div class="form-group">
                                    <label>Option {{ $key + 1 }}</label>
                                    <input type="text" name="questions[{{ $index }}][options][{{ $key }}]" class="form-control" value="{{ old("questions.$index.options.$key", $option->option) }}">
                                    <label>
                                        <input type="radio" name="questions[{{ $index }}][correct]" value="{{ $key }}" {{ old("questions.$index.correct") == $key ? 'checked' : ($quiz->correct_option == $key ? 'checked' : '') }}> Correct
                                    </label>
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
                    <h4>Question ${questionCount}</h4>
                    <div class="form-group">
                        <label>Enter Question</label>
                        <input type="text" name="questions[${questionCount}][question]" class="form-control" required>
                    </div>
                    <div id="answers-${questionCount}" class="answers-section">
                        <div class="form-group">
                            <label>Option 1</label>
                            <input type="text" name="questions[${questionCount}][options][0]" class="form-control" required>
                            <label>
                                <input type="radio" name="questions[${questionCount}][correct]" value="0"> Correct
                            </label>
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
                <div class="form-group">
                    <label>Option ${answerCount + 1}</label>
                    <input type="text" name="questions[${questionId}][options][${answerCount}]" class="form-control" required>
                    <label>
                        <input type="radio" name="questions[${questionId}][correct]" value="${answerCount}"> Correct
                    </label>
                </div>
            `;
        $(`#answers-${questionId}`).append(answerHtml);
    });
});
{{--$(document).on('click', '.remove-video', function() {--}}
    {{--var videoIndex = $(this).data('index');--}}
    {{--var videoPath = $(this).data('video');--}}

    {{--// Confirm before deletion--}}
    {{--if (confirm("Are you sure you want to delete this video?")) {--}}
        {{--$.ajax({--}}
            {{--url: '{{ route('--}}
            {{--trainings.remove_video ', $training->id) }}',--}}
            {{--method: 'POST',--}}
            {{--data: {--}}
                {{--_token: '{{ csrf_token() }}',--}}
                {{--video_path: videoPath--}}
            {{--},--}}
            {{--success: function(response) {--}}
                {{--if (response.success) {--}}
                    {{--// Remove the video from the UI--}}
                    {{--$('#video-' + videoIndex).remove();--}}
                    {{--alert("Video removed successfully!");--}}
                {{--} else {--}}
                    {{--alert("Error removing video.");--}}
                {{--}--}}
            {{--},--}}
            {{--error: function() {--}}
                {{--alert("An error occurred.");--}}
            {{--}--}}
        {{--});--}}
    {{--}--}}
{{--});--}}
</script>
@stop

@stop