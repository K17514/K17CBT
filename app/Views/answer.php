<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= esc($webDetail['title'] ?? 'Default Title'); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('images/' . ($webDetail['logo'] ?? 'default-logo.png')); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>
<body class="bg-purple-50">
<div class="flex flex-col items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-4xl p-6">
        
        <!-- Exam Title & Navigation -->
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-gray-700 font-semibold uppercase"><?= htmlspecialchars($exam->nama_exam) ?></h1>
            <!-- Timer Display -->
<div id="countdown" class="text-lg font-semibold text-red-500"></div>


            <a class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center" href="javascript:history.back()">
                <i class="fas fa-home"></i>
            </a>
        </div>

        <!-- Question Number Navigation -->
        <div class="flex space-x-2 mb-4">
            <?php foreach ($questions as $index => $q): ?>
                <button class="question-nav-btn bg-gray-300 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center <?= ($index == 0) ? 'bg-purple-500 text-white' : '' ?>" data-question="<?= $index ?>">
                    <?= $index + 1 ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Exam Form -->
        <form id="examForm" action="<?= base_url('home/submit') ?>" method="POST">

    <input type="hidden" name="id_exam" value="<?= $exam->id_exam ?>">

    <!-- Question Section -->
    <?php foreach ($questions as $index => $q): ?>
        <div class="question-container <?= ($index == 0) ? '' : 'hidden' ?>" data-question="<?= $index ?>">
            
            <!-- Question Text -->
            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <p class="text-gray-700 mb-4"><?= htmlspecialchars($q->question) ?></p>
            </div>

            <input type="hidden" name="answers[<?= $q->id_question ?>]" id="answer-<?= $q->id_question ?>">

             <!-- Tombol Flag -->
        <button type="button" class="flag-btn bg-gray-200 text-gray-700 rounded-lg px-4 py-2 mb-4" data-question-index="<?= $index ?>">
            Flag Soal
        </button>

            <?php if (empty($q->right_option)): ?>
                <!-- Essay Answer Input with Quill -->
                <div id="editor-<?= $q->id_question ?>" class="w-full p-3 border rounded-lg bg-white"></div>
                <input type="hidden" name="answers[<?= $q->id_question ?>]" id="essay-answer-<?= $q->id_question ?>">
            <?php else: ?>
                <!-- Multiple Choice Options -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <?php foreach ($q->options as $opt): ?>
                        <button type="button" 
                        class="option-btn bg-white text-gray-700 rounded-lg p-4 flex items-center space-x-2 border border-gray-300"
                        data-question-id="<?= $q->id_question ?>"
                        data-option="<?= $opt->option ?>">
                        <span class="bg-white text-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
                            <?= $opt->option ?>
                        </span>
                        <span><?= htmlspecialchars($opt->description) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- Navigation Buttons -->
    <div class="flex justify-between">
        <button type="button" id="prev-btn" class="bg-purple-100 text-purple-500 rounded-lg px-4 py-2">Sebelumnya</button>
        <button type="button" id="next-btn" class="bg-purple-500 text-white rounded-lg px-4 py-2">Selanjutnya</button>
        <button type="submit" id="submit-btn" onclick="this.disabled=true; this.form.submit();" class="bg-green-500 text-white rounded-lg px-4 py-2 hidden">Submit</button>
    </div>
</form>
    </div>
</div>

<!-- JavaScript for Navigation & Answer Selection -->
<script>
    let currentQuestion = 0;
    const totalQuestions = <?= count($questions) ?>;
    const questions = document.querySelectorAll('.question-container');

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.classList.toggle('hidden', i !== index);
        });

        document.querySelectorAll('.question-nav-btn').forEach((btn, i) => {
            btn.classList.toggle('bg-purple-500', i === index);
            btn.classList.toggle('text-white', i === index);
            btn.classList.toggle('bg-gray-300', i !== index);
        });

        document.getElementById('prev-btn').style.display = (index === 0) ? 'none' : 'inline-block';
        document.getElementById('next-btn').style.display = (index === totalQuestions - 1) ? 'none' : 'inline-block';
        document.getElementById('submit-btn').style.display = (index === totalQuestions - 1) ? 'inline-block' : 'none';
    }

    document.getElementById('next-btn').addEventListener('click', function() {
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    });

    document.getElementById('prev-btn').addEventListener('click', function() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    });

    document.querySelectorAll('.question-nav-btn').forEach((btn, index) => {
        btn.addEventListener('click', function() {
            currentQuestion = index;
            showQuestion(currentQuestion);
        });
    });

    document.querySelectorAll('.option-btn').forEach(button => {
        button.addEventListener('click', function() {
            let questionId = this.dataset.questionId;
            document.querySelectorAll(`.option-btn[data-question-id="${questionId}"]`).forEach(btn => {
                btn.classList.remove("bg-purple-500", "text-white");
                btn.classList.add("bg-white", "text-gray-700");
            });

            this.classList.remove("bg-white", "text-gray-700");
            this.classList.add("bg-purple-500", "text-white");

            document.getElementById(`answer-${questionId}`).value = this.dataset.option;
        });
    });

    showQuestion(0);


// Ensure PHP provides correct timestamps
let startTime = <?= isset($exam_detail->date_of_exam) ? strtotime($exam_detail->date_of_exam) * 1000 : 'new Date().getTime()' ?>;
let timeLimit = <?= !empty($exam->time_limit) ? ($exam->time_limit * 60 * 1000) : 'null' ?>; // Convert minutes to milliseconds or null for no limit
let endTime = timeLimit ? startTime + timeLimit : null;

console.log("Start Time from Database:", new Date(startTime));
console.log("Time Limit:", timeLimit ? timeLimit / 60000 + " minutes" : "No Time Limit");
if (endTime) console.log("End Time:", new Date(endTime));

function updateTimer() {
    let now = new Date().getTime();

    if (endTime) {
        let remainingTime = Math.max(0, endTime - now);
        let minutes = Math.floor(remainingTime / (1000 * 60));
        let seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        document.getElementById('countdown').innerText = `${minutes}m ${seconds}s`;

        // if (remainingTime <= 0) {
        //     document.getElementById('countdown').innerText = "Time's Up!";
        //     document.getElementById('submit-btn').disabled = true;
        //     document.querySelectorAll('.option-btn').forEach(btn => btn.disabled = true);
        //     document.getElementById('examForm').submit();
        // }
    } else {
        document.getElementById('countdown').innerText = "No Time Limit";
    }
}

// Update every second
setInterval(updateTimer, 1000);
updateTimer();

 document.addEventListener("DOMContentLoaded", function () {
        <?php foreach ($questions as $q): ?>
            <?php if (empty($q->right_option)): ?>
                var quill<?= $q->id_question ?> = new Quill("#editor-<?= $q->id_question ?>", {
                    theme: "snow",
                    placeholder: "Write your answer...",
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, false] }],
                            ["bold", "italic", "underline"],
                            [{ list: "ordered" }, { list: "bullet" }],
                            ["link"],
                            ["clean"]
                        ]
                    }
                });

                // Update hidden input on text change
                quill<?= $q->id_question ?>.on("text-change", function () {
                    document.getElementById("essay-answer-<?= $q->id_question ?>").value = quill<?= $q->id_question ?>.root.innerHTML;
                });
            <?php endif; ?>
        <?php endforeach; ?>
    });

   document.querySelectorAll('.flag-btn').forEach(button => {
          button.addEventListener('click', function() {
              let questionIndex = this.dataset.questionIndex;
              let navButton = document.querySelector(`.question-nav-btn[data-question='${questionIndex}']`);
              
              if (navButton.classList.contains('bg-red-500')) {
                  navButton.classList.remove('bg-red-500', 'text-white');
                  navButton.classList.add('bg-gray-300', 'text-gray-700');
              } else {
                  navButton.classList.remove('bg-gray-300', 'text-gray-700');
                  navButton.classList.add('bg-red-500', 'text-white');
              }
          });
      });
    document.querySelectorAll('.flag-btn').forEach(button => {
        button.addEventListener('click', function() {
            let questionId = this.dataset.questionId;
            let navButton = document.querySelector(`.question-nav-btn[data-question="${questionId}"]`);

            // Toggle warna tombol flag
            this.classList.toggle("bg-gray-300");
            this.classList.toggle("text-gray-700");
            this.classList.toggle("bg-red-500");
            this.classList.toggle("text-white");

            // Toggle warna nomor soal di navigasi
            if (navButton) {
                navButton.classList.toggle("bg-red-500");
                navButton.classList.toggle("text-white");
                navButton.classList.toggle("bg-gray-300");
                navButton.classList.toggle("text-gray-700");
            }
        });
    });


</script>
</body>
</html>
