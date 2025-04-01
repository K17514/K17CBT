
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
<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Attempts Detail   
    </div></h2>
        
</header>



<div class="container mx-auto p-4">
<section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center"><?= $exam_name ?> - Attempt Details</h2>

    <!-- Question Number Navigation -->
    <div class="flex space-x-2 mb-4 justify-center">
        <?php foreach ($questions as $index => $q): ?>
            <button class="question-nav-btn bg-gray-300 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center"
                data-question="<?= $index ?>">
                <?= $index + 1 ?>
            </button>
        <?php endforeach; ?>
    </div>

   <?php foreach ($questions as $index => $q): ?>
    <div class="border border-gray-300 p-4 mb-4 rounded-lg shadow-md bg-white">
        <!-- Question Text -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <p class="text-gray-700 font-semibold"><?= ($index + 1) . ". " . htmlspecialchars($q->question) ?></p>
        </div>

        <!-- Answer Box -->
        <div class="border border-gray-300 p-4 rounded-lg shadow-md bg-white">
            <?php if (empty($q->right_option)): ?>
                <!-- Essay Answer -->
                <p class="text-gray-700 font-semibold mb-2">Your Answer:</p>
                 <textarea class="w-full p-2 border border-gray-300 rounded-lg bg-white shadow-md" 
                          rows="4" readonly><?= strip_tags($q->chosen_option ?? 'No answer provided') ?></textarea>
            <?php else: ?>
                <!-- Multiple Choice Options -->
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($q->options as $opt): ?>
                     <div class="option-btn rounded-lg p-4 flex items-center space-x-2 border border-gray-300
                     <?php 
                     if ($q->chosen_option === $opt->option) {
                         echo $q->is_correct ? 'bg-green-500 text-white' : 'bg-red-500 text-white';
                     } elseif (!$q->is_correct && $q->correct_answer === $opt->option) {
                         echo 'bg-green-500 text-white';
                     } else {
                         echo 'bg-white text-gray-700';
                     }
                 ?>">

                 <span class="rounded-full w-8 h-8 flex items-center justify-center">
                    <?= $opt->option ?>
                </span>
                <span><?= htmlspecialchars($opt->description) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

</section>
</div>

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


</script>


