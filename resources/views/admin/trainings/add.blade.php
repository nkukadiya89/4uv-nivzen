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

    // Create a new video container
    const videoDiv = document.createElement("div");
    videoDiv.className = "video-container mb-4 border p-3 rounded";
    videoDiv.setAttribute("id", `video-${videoIndex}`);

    videoDiv.innerHTML = `
        <h4 class="d-flex justify-content-between align-items-center">
            Video ${videoIndex + 1}
            <button type="button" class="btn btn-danger btn-sm" onclick="removeVideo(${videoIndex})">Remove</button>
        </h4>

        <div class="form-group row">
            <label class="col-lg-3 form-label">Video Title<span class="required">*</span></label>
            <div class="col-lg-6">
                <input type="text" name="videos[${videoIndex}][title]" class="form-control required" placeholder="Enter video title" />
            </div>
        </div>

        <div class="mcq-section mb-4">
            <h5 class="mb-3">Questions</h5>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion(${videoIndex})">Add Question</button>
            <div id="questions-container-${videoIndex}"></div> <!-- Ensure this exists -->
        </div>
    `;

    videoSection.appendChild(videoDiv);

    // Initialize indexes properly
    questionIndex[videoIndex] = 0;
    optionIndex[videoIndex] = {};
    videoIndex++;
}

function removeVideo(index) {
     let videoElement = document.getElementById(`video-${index}`);
    if (videoElement) {
        videoElement.remove();
        // Update the indexes of remaining videos
        const videoContainers = document.querySelectorAll(".video-container");
        videoIndex = 0; // Reset the video index
        questionIndex = {}; // Reset question indexes
        optionIndex = {}; // Reset option indexes

        videoContainers.forEach((videoDiv, newIndex) => {
            videoDiv.id = `video-${newIndex}`;
            videoDiv.querySelector("h4").innerHTML = `Video ${newIndex + 1} <button type="button" class="btn btn-danger btn-sm" onclick="removeVideo(${newIndex})">Remove</button>`;

            // Update name attributes of input fields
            videoDiv.querySelectorAll("input, textarea").forEach(input => {
                input.name = input.name.replace(/\[videos\]\[\d+\]/, `[videos][${newIndex}]`);
            });

            // Update questions container ID
            const questionsContainer = videoDiv.querySelector(`[id^="questions-container-"]`);
            if (questionsContainer) {
                questionsContainer.id = `questions-container-${newIndex}`;
            }

            questionIndex[newIndex] = 0;
            optionIndex[newIndex] = {};
        });

        videoIndex = videoContainers.length;
    } else {
        console.warn(`Video element with ID video-${index} not found.`);
    }
}

function addQuestion(videoIdx) {
    console.log(`🔹 Adding question to video ${videoIdx}...`);

    // Find the correct container
    const questionsContainer = document.getElementById(`questions-container-${videoIdx}`);
    if (!questionsContainer) {
        console.error(`❌ Questions container for video ${videoIdx} not found!`);
        return;
    }

    // Get current question count
    const questionIdx = questionsContainer.children.length;
    console.log(`🆕 New question index: ${questionIdx}`);

    // Create question HTML
    const questionHTML = `
        <div id="question-container-${videoIdx}-${questionIdx}" class="question-item">
            <label for="question-${videoIdx}-${questionIdx}">Question ${questionIdx + 1}:</label>
            <input type="text" id="question-${videoIdx}-${questionIdx}" name="videos[${videoIdx}][quizzes][${questionIdx}][question]" required>

            <div id="options-container-${videoIdx}-${questionIdx}" class="options-container"></div>

            <button type="button" onclick="addOption(${videoIdx}, ${questionIdx})">Add Option</button>
            <button type="button" onclick="removeQuestion(${videoIdx}, ${questionIdx})">Remove Question</button>
        </div>
    `;

    // Append the question
    questionsContainer.insertAdjacentHTML("beforeend", questionHTML);

    console.log(`✅ Question ${questionIdx} added successfully to video ${videoIdx}.`);
}




function removeQuestion(videoIdx, questionIdx) {
    console.log(`Removing question ${questionIdx} from video ${videoIdx}...`);

    // Remove the question
    document.getElementById(`question-container-${videoIdx}-${questionIdx}`).remove();

    // Update indexes for remaining questions
    const questionsContainer = document.getElementById(`questions-container-${videoIdx}`);
    const remainingQuestions = questionsContainer.querySelectorAll("[id^=question-container-]");

    questionIndex[videoIdx] = 0; // Reset question index for this video
    optionIndex[videoIdx] = {}; // Reset option index for reordering

    remainingQuestions.forEach((questionDiv, newIdx) => {
        const oldQuestionId = questionDiv.id.split("-").pop();

        // Update the question div ID
        questionDiv.id = `question-container-${videoIdx}-${newIdx}`;

        // Update the heading number
        const questionTitle = questionDiv.querySelector("h4");
        questionTitle.innerHTML = `Question ${newIdx + 1}
            <button type="button" class="btn btn-danger btn-sm float-right" onclick="removeQuestion(${videoIdx}, ${newIdx})">Remove</button>`;

        // Update input field names
        const inputField = questionDiv.querySelector("input[name^='videos']");
        if (inputField) {
            inputField.name = `videos[${videoIdx}][quizzes][${newIdx}][question]`;
        }

        // Update the options container ID
        const optionsContainer = questionDiv.querySelector("[id^=options-container-]");
        if (optionsContainer) {
            optionsContainer.id = `options-container-${videoIdx}-${newIdx}`;
        }

        // Store new question index
        questionIndex[videoIdx] = newIdx + 1;
        optionIndex[videoIdx][newIdx] = optionIndex[videoIdx][oldQuestionId] || 0; // Preserve option count
    });

    console.log(`✅ Questions for video ${videoIdx} reordered.`);
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
    optionDiv.id = `option-${videoId}-${questionId}-${optionId}`;
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
    console.log(`🔹 Removing option ${optionIdx} from question ${questionIdx} (video ${videoIdx})...`);

    // Find and remove the option element
    const optionElement = document.getElementById(`option-${videoIdx}-${questionIdx}-${optionIdx}`);
    if (!optionElement) {
        console.error(`❌ Option element not found: option-${videoIdx}-${questionIdx}-${optionIdx}`);
        return;
    }
    optionElement.remove();

    // Find the options container
    const optionsContainer = document.getElementById(`options-container-${videoIdx}-${questionIdx}`);
    if (!optionsContainer) {
        console.error(`❌ Options container not found: options-container-${videoIdx}-${questionIdx}`);
        return;
    }

    // Get all remaining options
    const remainingOptions = Array.from(optionsContainer.children);
    console.log(`🔍 Remaining options count: ${remainingOptions.length}`);

    // Renumber remaining options
    remainingOptions.forEach((optionDiv, newIdx) => {
        console.log(`🔄 Updating option ${newIdx}`);

        // Update the option div ID
        optionDiv.id = `option-${videoIdx}-${questionIdx}-${newIdx}`;

        // Update label
        const optionLabel = optionDiv.querySelector("label");
        if (optionLabel) {
            optionLabel.textContent = `Option ${newIdx}:`;
            optionLabel.setAttribute("for", `option-input-${videoIdx}-${questionIdx}-${newIdx}`);
        }

        // Update input field
        const inputField = optionDiv.querySelector("input");
        if (inputField) {
            inputField.name = `videos[${videoIdx}][quizzes][${questionIdx}][options][${newIdx}]`;
            inputField.id = `option-input-${videoIdx}-${questionIdx}-${newIdx}`;
        }

        // Update remove button function
        const removeBtn = optionDiv.querySelector("button");
        if (removeBtn) {
            removeBtn.setAttribute("onclick", `removeOption(${videoIdx}, ${questionIdx}, ${newIdx})`);
        }
    });

    console.log(`✅ Options for question ${questionIdx} (video ${videoIdx}) successfully updated.`);
}



    </script>
@stop
@stop
