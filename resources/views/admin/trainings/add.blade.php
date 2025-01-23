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
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                            <div class="col-lg-6">
                                <input id="name" type="text" class="form-control required" name="name" value="{{ old('name') }}" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label" for="name">Upload Video<span class="required">*</span></label>
                            <div class="col-lg-6">
                            <input type="file" name="video" id="video" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="col-lg-3 col-form-label" for="name">Questions</label>
                            <div class="col-lg-6">
                                <div id="questions-section">
                                    @foreach (old('questions', []) as $index => $question)
                                        <div class="question-group" id="question-{{ $index }}">
                                            <h4>Question {{ $index }}</h4>
                                            <div class="form-group">
                                                <label>Enter Question</label>
                                                <input type="text" id="questions_1_correct" name="questions[{{ $index }}][question]" class="form-control" value="{{ old("questions.$index.question") }}">
                                                @error("questions.$index.question")
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="answers-{{ $index }}" class="answers-section">
                                                @foreach (old("questions.$index.options", []) as $key => $option)
                                                    <div class="form-group row align-items-center">
                                                        <div class="col-lg-3">
                                                        <label>Option {{ $key + 1 }}</label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                        <input type="text" name="questions[{{ $index }}][options][{{ $key }}]" class="form-control" value="{{ old("questions.$index.options.$key") }}">
                                                        </div>
                                                        <div class="col-lg-3">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $index }}][correct][]" value="{{ $key }}"   {{ old("questions.$index.correct") == $key ? 'checked' : '' }} > Correct
                                                        </label>
                                                            @error("questions.$index.correct")
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <button type="button" class="btn btn-secondary add-answer" data-question="{{ $index }}">Add Answer Option</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-primary mt-2" id="add-question">Add New Question</button>
                            </div>

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
        $(document).ready(function() {
            let questionCount = {{ count(old('questions', [])) }};

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
                        <input type="text" name="questions[${questionCount}][question]" class="form-control">
                    </div>
                    <div id="answers-${questionCount}" class="answers-section">
                        <div class="form-group row align-items-center">
                            <div class="col-lg-3">
                                <label>Option 1</label>
                            </div>
                            <div class="col-lg-6">
                               <input type="text" name="questions[${questionCount}][options][0]" class="form-control">
                            </div>
                            <div class="col-lg-3">
                            <label>
                                <input type="radio" name="questions[${questionCount}][correct]" value="0"> Correct
                            </label>
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
                <div class="form-group row align-items-center">
                    <div class="col-lg-3">
                      <label>Option ${answerCount + 1}</label>
                    </div>
                    <div class="col-lg-6">
                    <input type="text" name="questions[${questionId}][options][${answerCount}]" class="form-control">
                    </div>
                    <div class="col-lg-3">
                    <label>
                        <input type="radio" name="questions[${questionId}][correct]" value="${answerCount}"> Correct
                    </label>
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

            // Reindex Questions
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
        });
    </script>
@stop
@stop
