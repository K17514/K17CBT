<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($webDetail['title'] ?? 'Default Title'); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('images/' . ($webDetail['logo'] ?? 'default-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0"></script>
</head>
<body class="bg-purple-50 min-h-screen flex flex-col justify-center items-center p-4">

<div class="w-full max-w-3xl">
    <!-- Exam Result Card -->
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Exam Result</h1>

<?php if (!isset($result->exam_score) || $result->exam_score === null): ?>
    <p class="text-xl text-yellow-600 font-semibold">Pending</p>
    <p class="text-lg text-gray-700">Your score is currently being evaluated.</p>

<?php else: ?>
   <p class="text-xl text-gray-700">Your Score: <span class="font-bold"><?= $result->exam_score ?>%</span></p>
   <p class="text-2xl mt-4 font-semibold <?= ($result->exam_result == 'lulus') ? 'text-green-600' : 'text-red-600' ?>">
       <?= ucfirst($result->exam_result) ?>
   </p>
   <p>Time Taken: <span class="font-semibold"><?= $result->time_taken ?? 'N/A' ?></span></p>
<?php endif; ?>



        <!-- Tombol Kembali -->
        <a href="<?= base_url('home/detail/' . $result->id_exam) ?>" class="mt-6 inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition">
            Back to Exams
        </a>
    </div>
</div>

        <!-- Leaderboard (Hidden if Pending) -->
        <?php if (!$isPending): ?>
        <div class="bg-white shadow-lg rounded-lg p-8 mt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Leaderboard</h2>
            <table class="w-full text-lg border-collapse table">
                <thead class="table-purple text-white">
                    <tr>
                        <th class="p-3 text-left">Rank</th>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-right">Score</th>
                        <th class="p-3 text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($leaderboard as $user): ?>
                    <tr class="border-b hover:bg-purple-50 transition"> 
                        <td class="p-3 font-medium"><?= $rank++ ?></td>
                        <td class="p-3 flex items-center">
                            <img src="<?= base_url('images/' . ($user->foto ?: 'default.png')) ?>" alt="Profile Picture" class="w-10 h-10 rounded-full mr-3">
                            <span><?= esc($user->username) ?></span>
                        </td>
                        <td class="p-3 text-right font-bold">
                            <?= is_null($user->exam_score) ? 'Pending' : $user->exam_score . '%' ?>
                        </td>
                        <td class="p-3 text-right font-bold"><?= esc($user->created_at) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.3.2/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let examScore = <?= json_encode($isPending ? null : (int) $result->exam_score) ?>;
        console.log("Exam Score:", examScore); // Debugging

        if (examScore === null || examScore === "null" || examScore === "") {
            console.log("Pending result detected. No confetti.");
            return;
        }

        // Pastikan examScore dalam bentuk angka
        examScore = parseInt(examScore);

        // Trigger confetti only for a perfect score (100)
        setTimeout(() => {
            if (examScore === 100) {
                console.log("Triggering confetti!");
                confetti({
                    particleCount: 300,
                    spread: 200,
                    origin: { y: 0.6 }
                });

                setTimeout(() => {
                    confetti({
                        particleCount: 150,
                        spread: 200,
                        origin: { y: 0.6 }
                    });
                }, 1000);
            }
        }, 500); // Delay rendering
    });
</script>


</body>
</html>
