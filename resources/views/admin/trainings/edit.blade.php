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
                                    <div class="video-container mb-4 border p-3 rounded">

                                        <h4 class="mb-3">Video {{ $videoIndex + 1 }}</h4>
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
                                                        <img id="current-thumnail" src="{{ asset('storage/' . $video->thumbnail_url) }}" alt="Thumbnail" class="img-thumbnail" width="100"></p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mcq-section mb-4">
                                            <h5 class="mb-3">Questions</h5>
                                            @foreach($video->quizzes as $quizIndex => $quiz)
                                                <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][id]" value="{{ $quiz->id }}">
                                                <div id="question-container-{{ $videoIndex }}-{{ $quizIndex }}" class="mb-4 border p-3 rounded">
                                                    <div class="form-group row">
                                                        <div class="col-lg-12">
                                                            <h4>Question {{ $quizIndex + 1 }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">Question:</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][question]" class="form-control" placeholder="Enter question" value="{{ $quiz->question }}" required />
                                                        </div>
                                                    </div>
                                                    @foreach($quiz->options as $optionIndex => $option)
                                                        <input type="hidden" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                                        <div class="form-group row mb-3">
                                                            <label class="col-lg-3 col-form-label">Option {{ $optionIndex + 1 }}:</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][options][{{ $optionIndex }}][option]" class="form-control" placeholder="Enter option" value="{{ $option->option }}" required />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="videos[{{ $videoIndex }}][quizzes][{{ $quizIndex }}][correct_option]" value="{{ $optionIndex }}" {{ $option->is_correct ? 'checked' : '' }} />
                                                                    <label class="form-check-label">Correct</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion({{ $videoIndex }})">Add Question</button>
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
        let videoIndex = {{ isset($training->videoLessons) ? count($training->videoLessons) : 0 }};
        let questionIndex = {};
        let optionIndex = {};

        // Initialize indexes for existing videos, questions, and options
        @if(isset($training->videoLessons))
                @foreach($training->videoLessons as $videoIndexKey => $video)
            questionIndex[{{ $videoIndexKey }}] = {{ count($video->quizzes) }};
        optionIndex[{{ $videoIndexKey }}] = {};
        @foreach($video->quizzes as $quizIndexKey => $quiz)
            optionIndex[{{ $videoIndexKey }}][{{ $quizIndexKey }}] = {{ count($quiz->options) }};
        @endforeach
        @endforeach
        @endif

        // Add Video Function
        function addVideo() {
            let videoHTML = `
            <div class="video-container mb-4 border p-3 rounded">
                <h4 class="mb-3">Video ${videoIndex + 1}</h4>
                <div class="form-group row">
                    <label class="col-lg-3 form-label">Video Title</label>
                    <div class="col-lg-6">
                        <input type="text" name="videos[${videoIndex}][title]" class="form-control required" placeholder="Enter video title" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 form-label">Video Description</label>
                    <div class="col-lg-6">
                        <textarea name="videos[${videoIndex}][description]" class="form-control" rows="3" placeholder="Enter video description"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 form-label">Upload Video</label>
                    <div class="col-lg-6">
                        <input type="file" name="videos[${videoIndex}][video]" class="form-control" accept="video/*" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 form-label">Upload Thumbnail</label>
                    <div class="col-lg-6">
                        <input type="file" name="videos[${videoIndex}][thumbnail]" class="form-control" accept="image/*" />
                    </div>
                </div>
                <div class="mcq-section mb-4">
                    <h5 class="mb-3">Questions</h5>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion(${videoIndex})">Add Question</button>
                    <div id="question-container-${videoIndex}-0" class="question-container"></div>
                </div>
            </div>
        `;
            document.getElementById('video-section').insertAdjacentHTML('beforeend', videoHTML);
            questionIndex[videoIndex] = 0; // Reset question index for new video
            videoIndex++;
        }

        // Add Question Function
        // Add Question Function
        function addQuestion(videoIdx) {
            let questionHTML = `
    <div id="question-container-${videoIdx}-${questionIndex[videoIdx]}" class="mb-4 border p-3 rounded">
        <div class="form-group row">
            <div class="col-lg-12">
                <h4>Question ${questionIndex[videoIdx] + 1}</h4>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">Question:</label>
            <div class="col-lg-9">
                <input type="text" name="videos[${videoIdx}][quizzes][${questionIndex[videoIdx]}][question]" class="form-control" placeholder="Enter question" required />
            </div>
        </div>
        <div class="options-section" id="options-section-${videoIdx}-${questionIndex[videoIdx]}">
            <!-- Dynamic Options will be inserted here -->
                <div class="form-group row">
                <label class="col-lg-3 col-form-label">Option 1:</label>
                <div class="col-lg-6">
                <input type="text" name="videos[${videoIdx}][quizzes][${questionIndex[videoIdx]}][options][0][option]" class="form-control" placeholder="Enter option" required />
                </div>
                <div class="col-lg-3">
                <div class="form-check">
                <input class="form-check-input" type="radio" name="videos[${videoIdx}][quizzes][${questionIndex[videoIdx]}][correct_option]" value="0" />
                <label class="form-check-label">Correct</label>
                </div>
                </div>
                </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addOption(${videoIdx}, ${questionIndex[videoIdx]})">Add Option</button>
                </div>
                `;
    document.getElementById(`question-container-${videoIdx}-0`).insertAdjacentHTML('beforeend', questionHTML);
    questionIndex[videoIdx]++;
}

// Add Option Function
function addOption(videoIdx, questionIdx) {
    let optionCount = document.querySelectorAll(`#options-section-${videoIdx}-${questionIdx} .form-group`).length + 1;

    let optionHTML = `
                <div class="form-group row">
                <label class="col-lg-3 col-form-label">Option ${optionCount}:</label>
                <div class="col-lg-6">
                <input type="text" name="videos[${videoIdx}][quizzes][${questionIdx}][options][${optionCount - 1}][option]" class="form-control" placeholder="Enter option" required />
                </div>
                <div class="col-lg-3">
                <div class="form-check">
                <input class="form-check-input" type="radio" name="videos[${videoIdx}][quizzes][${questionIdx}][correct_option]" value="${optionCount - 1}" />
                <label class="form-check-label">Correct</label>
                </div>
                </div>
                </div>
                `;

    document.getElementById(`options-section-${videoIdx}-${questionIdx}`).insertAdjacentHTML('beforeend', optionHTML);
}
    </script>

@stop
