<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function addQuestion(questionIndex = null, questionText = '') {
            const questionContainer = document.getElementById("questions");
            const newIndex = questionIndex ?? questionContainer.children.length + 1;
            
            const questionDiv = document.createElement("div");
            questionDiv.classList.add("p-4", "mb-4", "bg-white", "shadow-md", "rounded-lg");
            questionDiv.setAttribute("id", `question-${newIndex}`);
            
            questionDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <input type="text" name="questions[${newIndex}][question]" value="${questionText}" placeholder="Insert Question" class="w-full p-2 border rounded mb-2" required>
                    <button type="button" onclick="removeElement('question-${newIndex}')" class="text-red-500 text-xl ml-2">❌</button>
                </div>

                <div id="options-${newIndex}"></div>

                <button type="button" onclick="addOption(${newIndex})" class="bg-blue-500 text-white py-1 px-3 rounded">+ Add Option</button>
            `;
            
            questionContainer.appendChild(questionDiv);
        }

        function addOption(questionIndex, optionLabel = '', optionValue = '', isChecked = false) {
            const optionContainer = document.getElementById(`options-${questionIndex}`);
            const optionCount = optionContainer.children.length;
            const optionChar = optionLabel || String.fromCharCode(65 + optionCount);

            const optionDiv = document.createElement("div");
            optionDiv.classList.add("flex", "items-center", "gap-2", "mb-2");
            optionDiv.setAttribute("id", `option-${questionIndex}-${optionChar}`);

            optionDiv.innerHTML = `
                <input type="radio" name="questions[${questionIndex}][right_option]" value="${optionChar}" ${isChecked ? 'checked' : ''} required>
                <input type="text" name="questions[${questionIndex}][options][${optionChar}]" value="${optionValue}" placeholder="Option ${optionChar}" class="w-full p-2 border rounded" required>
                <button type="button" onclick="removeOption('option-${questionIndex}-${optionChar}', ${questionIndex})" class="text-red-500 text-lg">❌</button>
            `;
            
            optionContainer.appendChild(optionDiv);
        }

        function removeElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.remove();
            }
        }

        function removeOption(optionId, questionIndex) {
            const optionContainer = document.getElementById(`options-${questionIndex}`);
            if (optionContainer.children.length > 1) {
                removeElement(optionId);
            } else {
                alert("At least one option is required.");
            }
        }
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-yellow-500">
    <form action="<?= base_url('home/update') ?>" method="post" class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg flex gap-6">
        <div class="w-2/3">
            <h2 class="text-xl font-bold mb-4">Edit Exam</h2>
            <input type="hidden" name="id_exam" value="<?= $exam->id_exam ?>">
            <input type="text" name="nama_exam" value="<?= $exam->nama_exam ?>" placeholder="Exam Title" class="w-full p-2 border rounded mb-2" required>
            <div id="editor" class="h-40 border rounded mb-2"></div>
            <input type="hidden" name="deskripsi" id="hiddenDeskripsi" value= "<?= $exam->deskripsi ?>" required>

            <div id="questions"></div>
            
            <script>
                <?php foreach ($questions as $q): ?>
                    addQuestion(<?= $q->id_question ?>, "<?= addslashes($q->question) ?>");
                    <?php foreach ($q->options as $opt): ?>
                        addOption(<?= $q->id_question ?>, "<?= addslashes($opt->option) ?>", "<?= addslashes($opt->description) ?>", <?= $q->right_option == $opt->option ? 'true' : 'false' ?>);
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </script>

            <button type="button" onclick="addQuestion()" class="bg-green-500 text-white py-2 px-4 rounded">+ Add Question</button>
            <button type="submit" class="bg-purple-500 text-white py-2 px-6 rounded mt-4">Update Exam</button>
        </div>
<div class="w-1/3 bg-gray-100 p-4 rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-2">Exam Settings</h2>

    <!-- Start Date -->
    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2" data-target="exam_open" <?= $exam->exam_open ? 'checked' : '' ?>>
        Start Date & Time
    </label>
    <div class="flex items-center gap-2">
        <input type="datetime-local" id="exam_open" name="exam_open"
               value="<?= $exam->exam_open ? date('Y-m-d\TH:i', strtotime($exam->exam_open)) : '' ?>"
               class="w-full p-2 border rounded mb-2"
               <?= $exam->exam_open ? '' : 'disabled' ?>>
        <button type="button" class="text-red-500 text-lg" onclick="clearInput('exam_open')">❌</button>
    </div>

    <!-- End Date -->
    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2" data-target="exam_closed" <?= $exam->exam_closed ? 'checked' : '' ?>>
        End Date & Time
    </label>
    <div class="flex items-center gap-2">
        <input type="datetime-local" id="exam_closed" name="exam_closed"
               value="<?= $exam->exam_closed ? date('Y-m-d\TH:i', strtotime($exam->exam_closed)) : '' ?>"
               class="w-full p-2 border rounded mb-2"
               <?= $exam->exam_closed ? '' : 'disabled' ?>>
        <button type="button" class="text-red-500 text-lg" onclick="clearInput('exam_closed')">❌</button>
    </div>

    <!-- Attempts Allowed -->
    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2" data-target="allowed_attempt" <?= isset($exam->allowed_attempt) ? 'checked' : '' ?>>
        Attempts Allowed per User
    </label>
    <input type="number" id="allowed_attempt" name="allowed_attempt" 
           value="<?= isset($exam->allowed_attempt) ? $exam->allowed_attempt : '' ?>" 
           class="w-full p-2 border rounded mb-2" min="1"
           <?= isset($exam->allowed_attempt) ? '' : 'disabled' ?>>

    <!-- Minimum Score -->
    <label class="flex items-center mb-1">Minimum Score for Passing</label>
    <input type="number" name="min_score" value="<?= $exam->min_score ?>" class="w-full p-2 border rounded mb-2" min="1" required>

    <!-- Time Limit -->
    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2" data-target="time_limit" <?= isset($exam->time_limit) ? 'checked' : '' ?>>
        Time Limit
    </label>
    <div class="flex items-center">
        <input type="number" id="time_limit" name="time_limit"
               value="<?= isset($exam->time_limit) ? $exam->time_limit : '' ?>" 
               class="w-full p-2 border rounded mb-2"
               <?= isset($exam->time_limit) ? '' : 'disabled' ?>>
        <span class="ml-2">min</span>
    </div>
</div>



            <div class="mt-2">
                <input type="hidden" name="q_shuffle" value="0">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="q_shuffle" value="1">
                    <span>Shuffle Questions</span>
                </label>

                <input type="hidden" name="o_shuffle" value="0">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="o_shuffle" value="1">
                    <span>Shuffle Options</span>
                </label>
            </div>
        </div>
    </form>
    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write the exam description...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean']
                    ]
            }
        });

// Set initial content for Quill editor
var initialDeskripsi = `<?= addslashes($exam->deskripsi) ?>`; // Fetch from PHP
quill.root.innerHTML = initialDeskripsi; 

// On form submit, copy Quill content to hidden input
document.querySelector("form").addEventListener("submit", function () {
    document.getElementById("hiddenDeskripsi").value = quill.root.innerHTML; // For HTML content
    // OR
    // document.getElementById("hiddenDeskripsi").value = quill.getText(); // For plain text
     const dateInputs = ["exam_open", "exam_closed"];
    dateInputs.forEach(id => {
        const input = document.getElementById(id);
        if (!input.value) {
            input.value = "1969-12-31T18:00";
        }
    });
});
 document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".enable-checkbox").forEach(function (checkbox) {
            let inputField = document.getElementById(checkbox.dataset.target);
            inputField.dataset.originalName = inputField.name; // Store original name

            if (!checkbox.checked) {
                inputField.removeAttribute("name"); // Prevent submission
                inputField.disabled = true;
            }

            checkbox.addEventListener("change", function () {
                if (this.checked) {
                    inputField.disabled = false;
                    inputField.setAttribute("name", inputField.dataset.originalName);
                } else {
                    inputField.disabled = true;
                    inputField.removeAttribute("name"); // Prevent submission
                }
            });
        });
    });

    function clearInput(inputId) {
        document.getElementById(inputId).value = "";
    }

</script>
</body>
</html>
