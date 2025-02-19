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
                            <!-- First Video -->

                        </div>

                        <!-- Add Video Button -->
                        <button type="button" class="btn btn-success mb-4" onclick="addVideo()">Add Another Video
                        </button>

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
            let questionIndex = {0: 1}; // Track question indexes for each video
            let optionIndex = {0: {0: 2}}; // Track option indexes for each question of each video
            window.onload = function () {
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

                // Initialize indexes properly
                questionIndex[videoIndex] = 0;
                optionIndex[videoIndex] = {};
                videoIndex++;

            }

            function removeVideo(index) {
                let videoElement = document.getElementById(`video-${index}`);
                if (videoElement) {
                    videoElement.remove(); // Remove the video container

                    // Delete tracking data for the removed video
                    delete questionIndex[index];
                    delete optionIndex[index];

                    // Get all remaining video containers
                    const videoContainers = document.querySelectorAll(".video-container");

                    // Update global videoIndex based on the new count
                    videoIndex = videoContainers.length;

                    videoContainers.forEach((videoDiv, newIndex) => {
                        let oldIndex = videoDiv.id.split("-")[1]; // Extract old video index

                        // Update the video container ID
                        videoDiv.id = `video-${newIndex}`;

                        // Update the video heading and remove button
                        let heading = videoDiv.querySelector("h4");
                        heading.innerHTML = `Video ${newIndex + 1} <button type="button" class="btn btn-danger btn-sm" onclick="removeVideo(${newIndex})">Remove</button>`;

                        // Update all input and textarea fields inside the video container
                        videoDiv.querySelectorAll("input, textarea").forEach(input => {
                            input.name = input.name.replace(/\[videos\]\[\d+\]/, `[videos][${newIndex}]`);
                        });

                        // Update the questions container ID
                        let questionsContainer = videoDiv.querySelector(`[id^="questions-container-"]`);
                        if (questionsContainer) {
                            questionsContainer.id = `questions-container-${newIndex}`;

                            // ✅ Recalculate question indexes correctly
                            let questionItems = questionsContainer.querySelectorAll(".question-item");
                            questionItems.forEach((questionDiv, qIndex) => {
                                let oldQIndex = questionDiv.id.split("-")[3]; // Extract old question index

                                questionDiv.id = `question-container-${newIndex}-${qIndex}`;

                                let questionLabel = questionDiv.querySelector("label");
                                questionLabel.setAttribute("for", `question-${newIndex}-${qIndex}`);
                                questionLabel.textContent = `Question ${qIndex + 1}:`;

                                let questionInput = questionDiv.querySelector("input");
                                if (questionInput) {
                                    questionInput.id = `question-${newIndex}-${qIndex}`;
                                    questionInput.name = `videos[${newIndex}][quizzes][${qIndex}][question]`;
                                }

                                // Update add/remove buttons for questions
                                let addOptionButton = questionDiv.querySelector(`button[onclick^="addOption("]`);
                                if (addOptionButton) {
                                    addOptionButton.setAttribute("onclick", `addOption(${newIndex}, ${qIndex})`);
                                }

                                let removeQuestionButton = questionDiv.querySelector(`button[onclick^="removeQuestion("]`);
                                if (removeQuestionButton) {
                                    removeQuestionButton.setAttribute("onclick", `removeQuestion(${newIndex}, ${qIndex})`);
                                }

                                // Update the options container ID
                                let optionsContainer = questionDiv.querySelector(`[id^="options-container-"]`);
                                if (optionsContainer) {
                                    optionsContainer.id = `options-container-${newIndex}-${qIndex}`;

                                    // ✅ Update each option inside the question
                                    let optionItems = optionsContainer.querySelectorAll(".option-item");
                                    optionItems.forEach((optionDiv, optIndex) => {
                                        let oldOptIndex = optionDiv.id.split("-")[4]; // Extract old option index

                                        optionDiv.id = `option-${newIndex}-${qIndex}-${optIndex}`;

                                        let optionLabel = optionDiv.querySelector(`label[for^="option-"]`);
                                        if (optionLabel) {
                                            optionLabel.setAttribute("for", `option-${newIndex}-${qIndex}-${optIndex}`);
                                            optionLabel.textContent = `Option ${optIndex + 1}:`;
                                        }

                                        let optionInput = optionDiv.querySelector("input");
                                        if (optionInput) {
                                            optionInput.id = `option-${newIndex}-${qIndex}-${optIndex}`;
                                            optionInput.name = `videos[${newIndex}][quizzes][${qIndex}][options][${optIndex}][option]`;
                                        }

                                        let radioInput = optionDiv.querySelector(`input[type="radio"]`);
                                        if (radioInput) {
                                            radioInput.id = `correct-${newIndex}-${qIndex}-${optIndex}`;
                                            radioInput.name = `videos[${newIndex}][quizzes][${qIndex}][correct_option]`;
                                        }

                                        let removeOptionButton = optionDiv.querySelector(`button[onclick^="removeOption("]`);
                                        if (removeOptionButton) {
                                            removeOptionButton.setAttribute("onclick", `removeOption(${newIndex}, ${qIndex}, ${optIndex})`);
                                        }
                                    });
                                }
                            });

                            // Fix tracking indexes
                            questionIndex[newIndex] = questionItems.length;
                            optionIndex[newIndex] = optionIndex[oldIndex] || {0: 2};
                        } else {
                            console.warn(`❌ Questions container for video ${newIndex} not found!`);
                        }

                        // ✅ Update "Add Question" button onclick for each video
                        let addQuestionButton = videoDiv.querySelector(`button[onclick^="addQuestion("]`);
                        if (addQuestionButton) {
                            addQuestionButton.setAttribute("onclick", `addQuestion(${newIndex})`);
                        }
                    });

                    console.log("Updated videoIndex:", videoIndex);
                    console.log("Updated questionIndex:", questionIndex);
                    console.log("Updated optionIndex:", optionIndex);
                } else {
                    console.warn(`Video element with ID video-${index} not found.`);
                }
            }



            function addQuestion(videoIdx) {
                console.log("videoIdx", videoIdx);
                const questionsContainer = document.getElementById(`questions-container-${videoIdx}`);

                if (!questionsContainer) {
                    console.error(`❌ Questions container for video ${videoIdx} not found!`);
                    return;
                }

                // Initialize question index if not set
                if (!questionIndex[videoIdx]) {
                    questionIndex[videoIdx] = 0;
                }
                const questionIdx = questionIndex[videoIdx]++;

                // ✅ Reset option index for this new question
                if (!optionIndex[videoIdx]) {
                    optionIndex[videoIdx] = {};
                }
                optionIndex[videoIdx][questionIdx] = 0; // Reset for the new question

                const questionHTML = `
        <div id="question-container-${videoIdx}-${questionIdx}" class="question-item">
            <label for="question-${videoIdx}-${questionIdx}">Question ${questionIdx + 1}:</label>
            <input type="text" id="question-${videoIdx}-${questionIdx}"
                name="videos[${videoIdx}][quizzes][${questionIdx}][question]"
                class="form-control" required>

            <div id="options-container-${videoIdx}-${questionIdx}" class="options-container"></div>

            <button type="button" class="btn btn-secondary btn-sm" onclick="addOption(${videoIdx}, ${questionIdx})">
                Add Option
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(${videoIdx}, ${questionIdx})">
                Remove Question
            </button>
        </div>
    `;

                questionsContainer.insertAdjacentHTML("beforeend", questionHTML);
            }


            // function removeQuestion(videoIdx, questionIdx) {
            //     const questionContainer = document.getElementById(`question-container-${videoIdx}-${questionIdx}`);
            //
            //     if (questionContainer) {
            //         questionContainer.remove();
            //     }
            //     // ✅ Remove the reference from `optionIndex`
            //     if (optionIndex[videoIdx]) {
            //         delete optionIndex[videoIdx][questionIdx];
            //     }
            //     // Re-index remaining questions to maintain sequential order
            //     const questionsContainer = document.getElementById(`questions-container-${videoIdx}`);
            //     const questionItems = questionsContainer.querySelectorAll(".question-item");
            //
            //     questionItems.forEach((item, newIndex) => {
            //         const oldIndex = item.id.split("-").pop(); // Extract old index
            //         item.id = `question-container-${videoIdx}-${newIndex}`;
            //
            //         const label = item.querySelector("label");
            //         label.innerText = `Question ${newIndex + 1}:`;
            //         label.setAttribute("for", `question-${videoIdx}-${newIndex}`);
            //
            //         const input = item.querySelector("input");
            //         input.id = `question-${videoIdx}-${newIndex}`;
            //         input.name = `videos[${videoIdx}][quizzes][${newIndex}][question]`;
            //
            //         const optionsContainer = item.querySelector(".options-container");
            //         optionsContainer.id = `options-container-${videoIdx}-${newIndex}`;
            //
            //         const addOptionBtn = item.querySelector("button.btn-secondary");
            //         addOptionBtn.setAttribute("onclick", `addOption(${videoIdx}, ${newIndex})`);
            //
            //         const removeBtn = item.querySelector("button.btn-danger");
            //         removeBtn.setAttribute("onclick", `removeQuestion(${videoIdx}, ${newIndex})`);
            //     });
            //
            //     // Update questionIndex to avoid gaps
            //     questionIndex[videoIdx] = questionItems.length;
            // }
            function removeQuestion(videoIdx, questionIdx) {
                const questionContainer = document.getElementById(`question-container-${videoIdx}-${questionIdx}`);

                if (questionContainer) {
                    questionContainer.remove();
                }

                // ✅ Remove the reference from `optionIndex`
                if (optionIndex[videoIdx]) {
                    delete optionIndex[videoIdx][questionIdx];
                }

                // Re-index remaining questions to maintain sequential order
                const questionsContainer = document.getElementById(`questions-container-${videoIdx}`);
                const questionItems = questionsContainer.querySelectorAll(".question-item");

                questionItems.forEach((item, newIndex) => {
                    const oldIndex = item.id.split("-").pop(); // Extract old index
                    item.id = `question-container-${videoIdx}-${newIndex}`;

                    const label = item.querySelector("label");
                    label.innerText = `Question ${newIndex + 1}:`;
                    label.setAttribute("for", `question-${videoIdx}-${newIndex}`);

                    const input = item.querySelector("input");
                    input.id = `question-${videoIdx}-${newIndex}`;
                    input.name = `videos[${videoIdx}][quizzes][${newIndex}][question]`;

                    // ✅ Update options-container ID
                    const optionsContainer = item.querySelector(".options-container");
                    if (optionsContainer) {
                        optionsContainer.id = `options-container-${videoIdx}-${newIndex}`;

                        // ✅ Reindex all option elements
                        const optionItems = optionsContainer.querySelectorAll(".form-group.row");
                        optionItems.forEach((option, optIndex) => {
                            option.id = `option-${videoIdx}-${newIndex}-${optIndex}`;

                            const label = option.querySelector("label");
                            label.setAttribute("for", `option-${videoIdx}-${newIndex}-${optIndex}`);
                            label.innerText = `Option ${optIndex + 1}:`;

                            const input = option.querySelector("input[type='text']");
                            input.id = `option-${videoIdx}-${newIndex}-${optIndex}`;
                            input.name = `videos[${videoIdx}][quizzes][${newIndex}][options][${optIndex}][option]`;

                            const radioInput = option.querySelector("input[type='radio']");
                            radioInput.name = `videos[${videoIdx}][quizzes][${newIndex}][correct_option]`;
                            radioInput.id = `correct-${videoIdx}-${newIndex}-${optIndex}`;

                            const removeBtn = option.querySelector("button.btn-danger");
                            removeBtn.setAttribute("onclick", `removeOption(${videoIdx}, ${newIndex}, ${optIndex})`);
                        });
                    }

                    // ✅ Update Add Option Button
                    const addOptionBtn = item.querySelector("button.btn-secondary");
                    addOptionBtn.setAttribute("onclick", `addOption(${videoIdx}, ${newIndex})`);

                    // ✅ Update Remove Question Button
                    const removeBtn = item.querySelector("button.btn-danger");
                    removeBtn.setAttribute("onclick", `removeQuestion(${videoIdx}, ${newIndex})`);
                });

                // ✅ Update questionIndex to avoid gaps
                questionIndex[videoIdx] = questionItems.length;
            }




    //         function addOption(videoIdx, questionIdx) {
    //             const optionsContainer = document.getElementById(`options-container-${videoIdx}-${questionIdx}`);
    //
    //             if (!optionsContainer) {
    //                 console.error(`❌ Options container for question ${questionIdx} in video ${videoIdx} not found!`);
    //                 return;
    //             }
    //
    //             // ✅ Ensure optionIndex exists
    //             if (!optionIndex[videoIdx]) {
    //                 optionIndex[videoIdx] = {};
    //             }
    //             if (optionIndex[videoIdx][questionIdx] === undefined) {
    //                 optionIndex[videoIdx][questionIdx] = 0;
    //             }
    //
    //             const optionIdx = optionIndex[videoIdx][questionIdx]++; // Get and increment option index
    //
    //             const optionHTML = `
    //     <div id="option-${videoIdx}-${questionIdx}-${optionIdx}" class="form-group row mb-3">
    //         <label class="col-lg-2 col-form-label" for="option-${videoIdx}-${questionIdx}-${optionIdx}">
    //             Option ${optionIdx + 1}:
    //         </label>
    //         <div class="col-lg-4">
    //             <input type="text" id="option-${videoIdx}-${questionIdx}-${optionIdx}"
    //                 name="videos[${videoIdx}][quizzes][${questionIdx}][options][${optionIdx}][option]"
    //                 class="form-control" placeholder="Enter option" required>
    //         </div>
    //         <div class="col-lg-3">
    //             <div class="form-check">
    //                 <input class="form-check-input" type="radio"
    //                     name="videos[${videoIdx}][quizzes][${questionIdx}][correct_option]"
    //                     value="${optionIdx}" id="correct-${videoIdx}-${questionIdx}-${optionIdx}">
    //                 <label class="form-check-label" for="correct-${videoIdx}-${questionIdx}-${optionIdx}">
    //                     Correct
    //                 </label>
    //             </div>
    //         </div>
    //         <div class="col-lg-3">
    //             <button type="button" class="btn btn-danger btn-sm"
    //                 onclick="removeOption(${videoIdx}, ${questionIdx}, ${optionIdx})">Remove</button>
    //         </div>
    //     </div>
    // `;
    //
    //             optionsContainer.insertAdjacentHTML("beforeend", optionHTML);
    //         }

            function addOption(videoIdx, questionIdx) {
                const optionsContainer = document.getElementById(`options-container-${videoIdx}-${questionIdx}`);

                if (!optionsContainer) {
                    console.error(`❌ Options container for question ${questionIdx} in video ${videoIdx} not found!`);
                    return;
                }

                // ✅ Count existing options dynamically to avoid gaps
                const existingOptions = optionsContainer.querySelectorAll(".form-group.row");
                const optionIdx = existingOptions.length; // Next available index

                const optionHTML = `
        <div id="option-${videoIdx}-${questionIdx}-${optionIdx}" class="form-group row mb-3">
            <label class="col-lg-2 col-form-label" for="option-${videoIdx}-${questionIdx}-${optionIdx}">
                Option ${optionIdx + 1}:
            </label>
            <div class="col-lg-4">
                <input type="text" id="option-${videoIdx}-${questionIdx}-${optionIdx}"
                    name="videos[${videoIdx}][quizzes][${questionIdx}][options][${optionIdx}][option]"
                    class="form-control" placeholder="Enter option" required>
            </div>
            <div class="col-lg-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio"
                        name="videos[${videoIdx}][quizzes][${questionIdx}][correct_option]"
                        value="${optionIdx}" id="correct-${videoIdx}-${questionIdx}-${optionIdx}">
                    <label class="form-check-label" for="correct-${videoIdx}-${questionIdx}-${optionIdx}">
                        Correct
                    </label>
                </div>
            </div>
            <div class="col-lg-3">
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeOption(${videoIdx}, ${questionIdx}, ${optionIdx})">Remove</button>
            </div>
        </div>
    `;

                optionsContainer.insertAdjacentHTML("beforeend", optionHTML);
            }


            function removeOption(videoIdx, questionIdx, optionIdx) {
                const optionContainer = document.getElementById(`option-${videoIdx}-${questionIdx}-${optionIdx}`);

                if (optionContainer) {
                    optionContainer.remove();
                }

                const optionsContainer = document.getElementById(`options-container-${videoIdx}-${questionIdx}`);

                if (!optionsContainer) {
                    console.warn(`Options container not found for videoIdx: ${videoIdx}, questionIdx: ${questionIdx}`);
                    return;
                }

                const optionItems = optionsContainer.querySelectorAll(".form-group");

                // ✅ Reset option index for this question
                if (!optionIndex[videoIdx]) optionIndex[videoIdx] = {};
                optionIndex[videoIdx][questionIdx] = optionItems.length;

                optionItems.forEach((item, newIndex) => {
                    item.id = `option-${videoIdx}-${questionIdx}-${newIndex}`;

                    const label = item.querySelector("label");
                    if (label) {
                        label.innerText = `Option ${newIndex + 1}:`;
                        label.setAttribute("for", `option-${videoIdx}-${questionIdx}-${newIndex}`);
                    }

                    const input = item.querySelector("input[type='text']");
                    if (input) {
                        input.id = `option-${videoIdx}-${questionIdx}-${newIndex}`;
                        input.name = `videos[${videoIdx}][quizzes][${questionIdx}][options][${newIndex}][option]`;
                    }

                    const radio = item.querySelector("input[type='radio']");
                    if (radio) {
                        radio.name = `videos[${videoIdx}][quizzes][${questionIdx}][correct_option]`;
                        radio.value = newIndex;
                        radio.id = `correct-${videoIdx}-${questionIdx}-${newIndex}`;
                    }

                    const radioLabel = item.querySelector("label[for^='correct']");
                    if (radioLabel) {
                        radioLabel.setAttribute("for", `correct-${videoIdx}-${questionIdx}-${newIndex}`);
                    }

                    const removeBtn = item.querySelector("button");
                    if (removeBtn) {
                        removeBtn.setAttribute("onclick", `removeOption(${videoIdx}, ${questionIdx}, ${newIndex})`);
                    }
                });

                // 🚨 If no options remain, remove the entire question
                if (optionItems.length === 0) {
                    console.log(`No options left for questionIdx: ${questionIdx}, removing question.`);
                    removeQuestion(videoIdx, questionIdx);
                }
            }

        </script>
    @stop
@stop
