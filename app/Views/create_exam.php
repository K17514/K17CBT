<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title><?= esc($webDetail['title'] ?? 'Default Title'); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('images/' . ($webDetail['logo'] ?? 'default-logo.png')); ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
     function addQuestion() {
    Swal.fire({
        title: "Select Question Type",
        text: "Choose the type of question you want to add.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Multiple Choice",
        cancelButtonText: "Essay",
    }).then((result) => {
        if (result.isConfirmed) {
            createQuestion("multiple");
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            createQuestion("essay");
        }
    });
}

function createQuestion(type) {
    const questionContainer = document.getElementById("questions");
    const questionIndex = questionContainer.children.length + 1;
    
    const questionDiv = document.createElement("div");
    questionDiv.classList.add("p-4", "mb-4", "bg-white", "shadow-md", "rounded-lg");
    questionDiv.setAttribute("id", `question-${questionIndex}`);

    let questionHTML = `
        <div class="flex justify-between items-center">
            <input type="text" name="questions[${questionIndex}][question]" placeholder="Insert Question" class="w-full p-2 border rounded mb-2" required>
            <button type="button" onclick="removeElement('question-${questionIndex}')" class="text-red-500 text-xl ml-2">❌</button>
        </div>
    `;

    if (type === "multiple") {
        questionHTML += `
            <div id="options-${questionIndex}"></div>
            <button type="button" onclick="addOption(${questionIndex})" class="bg-blue-500 text-white py-1 px-3 rounded">+ Add Option</button>
        `;
    }

    questionDiv.innerHTML = questionHTML;
    questionContainer.appendChild(questionDiv);

    if (type === "multiple") {
        addOption(questionIndex); // Tambahkan satu opsi default
    }
}


  

        function addOption(questionIndex) {
            const optionContainer = document.getElementById(`options-${questionIndex}`);
            const optionCount = optionContainer.children.length;
            const optionLabel = String.fromCharCode(65 + optionCount);
            
            const optionDiv = document.createElement("div");
            optionDiv.classList.add("flex", "items-center", "gap-2", "mb-2");
            optionDiv.setAttribute("id", `option-${questionIndex}-${optionLabel}`);

            optionDiv.innerHTML = `
                <input type="radio" name="questions[${questionIndex}][right_option]" value="${optionLabel}" required>
                <input type="text" name="questions[${questionIndex}][options][${optionLabel}]" placeholder="Option ${optionLabel}" class="w-full p-2 border rounded" required>
                <button type="button" onclick="removeOption('option-${questionIndex}-${optionLabel}', ${questionIndex})" class="text-red-500 text-lg">❌</button>
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
<body class="flex items-center justify-center min-h-screen bg-purple-500">
    <form action="<?= base_url('home/store') ?>" method="post" class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg flex gap-6">
        <div class="w-2/3">
            <h2 class="text-xl font-bold mb-4">Create Exam</h2>
            <input type="text" name="nama_exam" placeholder="Exam Title" class="w-full p-2 border rounded mb-2" required>
            <div id="editor" class="h-40 border rounded mb-2"></div>
            <input type="hidden" name="deskripsi" id="hiddenDeskripsi" required>
            <div id="questions"></div>
            <button type="button" onclick="addQuestion()" class="bg-yellow-500 text-white py-2 px-4 rounded">+ Add Question</button>
            <button type="submit" class="bg-purple-500 text-white py-2 px-6 rounded mt-4">Create Exam</button>
        </div>

       <div class="w-1/3 bg-gray-100 p-4 rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-2">Exam Settings</h2>

    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2">
        Start Date & Time
    </label>
    <input type="datetime-local" name="exam_open" class="w-full p-2 border rounded mb-2" disabled>

    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2">
        End Date & Time
    </label>
    <input type="datetime-local" name="exam_closed" class="w-full p-2 border rounded mb-2" disabled>

    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2">
        Attempts Allowed per User
    </label>
    <input type="number" name="allowed_attempt" class="w-full p-2 border rounded mb-2" min="1" disabled>

    <label class="flex items-center mb-1">
        Minimum Score for Passing
    </label>
    <input type="number" name="min_score" class="w-full p-2 border rounded mb-2" min="1" required>

    <label class="flex items-center mb-1">
        <input type="checkbox" class="enable-checkbox mr-2">
        Time Limit
    </label>
        <input type="number" name="time_limit" class="w-full p-2 border rounded mb-2" disabled>
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
      document.addEventListener("DOMContentLoaded", function () {
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

    document.querySelector("form").addEventListener("submit", function () {
        document.getElementById("hiddenDeskripsi").value = quill.root.innerHTML;

        // Periksa input datetime yang dinonaktifkan, set default value jika perlu
        document.querySelectorAll("input[type='datetime-local']").forEach(input => {
            if (input.disabled) {
                input.setAttribute("name", input.dataset.originalName); // Pastikan name ada
                input.value = "1969-12-31T18:00"; // Set nilai default
            }
        });
    });

    document.querySelectorAll(".enable-checkbox").forEach(function (checkbox) {
        let inputField = checkbox.closest("label").nextElementSibling;
        inputField.dataset.originalName = inputField.name; // Simpan nama asli
        inputField.removeAttribute("name"); // Hapus name jika disabled

        checkbox.addEventListener("change", function () {
            if (this.checked) {
                inputField.disabled = false;
                inputField.setAttribute("name", inputField.dataset.originalName);
            } else {
                inputField.disabled = true;
                inputField.removeAttribute("name");
            }
        });
    });

    // **Set nilai default untuk datetime-local jika kosong**
    document.querySelectorAll("input[type='datetime-local']").forEach(input => {
        if (!input.value) {
            input.value = "1969-12-31T18:00";
        }
    });
});


    </script>
</body>
</html>
