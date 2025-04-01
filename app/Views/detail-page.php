<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Detail Page</h2>
            </div>
        </div>
    </div>
</header>


<?php if (session()->get('level') == 1) { ?>
    <section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
        <div class="container">
            <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
                <div class="custom-block d-flex flex-column">
                    <form action="<?= base_url('admin/laporan_nilai') ?>" method="POST" target="_blank">
                        <h4 class=" mb-3">Generate PDF Report</h4>
                        <div class="row g-3 align-items-end">
                            <!-- Tanggal Awal -->
                            <div class="col-md-4">
                                <label for="tanggal_awal_3" class="form-label">Tanggal Awal</label>
                                <input type="datetime-local" id="tanggal_awal_3" class="form-control" name="tanggal_awal" required>
                            </div>

                            <input type="hidden" name="id_exam" value="<?= $child->id_exam ?>">

                            <!-- Tanggal Akhir -->
                            <div class="col-md-4">
                                <label for="tanggal_akhir_3" class="form-label">Tanggal Akhir</label>
                                <input type="datetime-local" id="tanggal_akhir_3" class="form-control" name="tanggal_akhir" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-4 d-grid">
                                <button class="btn custom-btn">
                                    <i class="bi bi-file-pdf-fill"></i> Generate PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </section>
<?php } ?>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
                <h2 class="mb-2" style="text-transform: uppercase;"><?= ($child->nama_exam) ?></h2>
                <p><?= ($child->deskripsi) ?></p>

                <div class="custom-block-info ml-lg-4">
                    <big>Questions: <span class="badge"><?= $child->total_questions ?></span></big>

                    <?php if (session()->get('id_user') == $child->created_by || session()->get('level') == 1 || session()->get('level') == 49) : ?>
                        <a href="<?= base_url('home/attempts_list/' . $child->id_exam) ?>" class="text-decoration-none">
                            <?php endif; ?>
                            <big class="ms-3">Played: <span class="badge"><?= $child->times_play ?? 0 ?></span></big>
                        </a>


                        <big><strong>Attempts Allowed:</strong> 
                            <span class="text-dark">
                                <?= (empty($child->allowed_attempt)) ? 'Unlimited' : $child->allowed_attempt ?>
                            </span>
                        </big>

                    </div>



                <div class="custom-block-info ml-lg-4 mt-2">
                    <big><strong>Exam Open:</strong> 
                        <span class="text-dark">
                            <?= ($child->exam_open == '1969-12-31 18:00:00') ? 'No Opening Time' : date('Y-m-d H:i', strtotime($child->exam_open)) ?>
                        </span>
                    </big>
                    <big><strong>Exam Closed:</strong> 
                        <span class="text-dark">
                            <?= ($child->exam_closed == '1969-12-31 18:00:00') ? 'No Closing Time' : date('Y-m-d H:i', strtotime($child->exam_closed)) ?>
                        </span>
                    </big>
                    <big><strong>Time Limit:</strong> 
                        <span class="text-dark">
                            <?= (empty($child->time_limit)) ? 'No Time Limit' : $child->time_limit . ' min' ?>
                        </span>
                    </big>

                </div> 

                <div class="custom-block-bottom d-flex justify-content-between align-items-center mt-3 mb-4">
                    <?php
date_default_timezone_set('Asia/Jakarta');

$current_time = date('Y-m-d H:i:s');
$exam_open = $child->exam_open;
$exam_closed = $child->exam_closed;
$time_limit = $child->time_limit;
$allowed_attempt = $child->allowed_attempt;
$attempt_count = count($attempts); // Hitung jumlah attempt user ini

// Special case: No Open/Close Time
$no_open_time = ($exam_open == '1969-12-31 18:00:00');
$no_close_time = ($exam_closed == '1969-12-31 18:00:00');

// Determine exam availability
$can_access = false;
if (!$no_open_time && !$no_close_time) {
    $can_access = ($current_time >= $exam_open && $current_time <= $exam_closed);
} elseif (!$no_open_time && $no_close_time) {
    $can_access = ($current_time >= $exam_open);
} elseif ($no_open_time && !$no_close_time) {
    $can_access = ($current_time <= $exam_closed);
} else {
    $can_access = true; // No restrictions
}

// Attempt & Time Limit
$unlimited_attempts = empty($allowed_attempt);
$remaining_attempts = $unlimited_attempts || ($attempt_count < $allowed_attempt);
$display_time_limit = !empty($time_limit) ? "$time_limit min" : "No Time Limit";
$display_attempts = $unlimited_attempts ? "Unlimited" : $allowed_attempt;

// Display button
if (!$can_access) {
    echo '<button class="btn btn-secondary ms-auto" disabled>Cannot Access</button>';
} elseif (!$remaining_attempts) {
    echo '<button class="btn btn-danger ms-auto" disabled>No More Attempt</button>';
} else {
    echo '<a href="' . base_url('home/answer/' . $child->id_exam) . '" class="btn custom-btn ms-auto">Answer Exam</a>';
}
?>

                </div>
            </div>
        </div>
    </div>  
</section>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
        <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Your Attempts</h2>
    <table class="w-full text-lg border-collapse table">
       <thead class="table-purple text-white">
            <tr class="bg-gray-800 text-white">
                <th class="p-3 text-left">Start Time</th>
                <th class="p-3 text-left">Submit Time</th>
                <th class="p-3 text-left">Time Taken</th>
                <th class="p-3 text-left">Result</th>
                <th class="p-3 text-left">Score</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $attempt): ?>
            <tr class="border-b hover:bg-gray-100">
                <td class="p-3"><?= $attempt->date_of_exam ?></td>
                <td class="p-3"><?= $attempt->date_of_submit ?: 'Not Submitted' ?></td>
                <td class="p-3"><?= $attempt->time_taken ?: 'N/A' ?></td>
                <td class="p-3 <?= ($attempt->exam_result == 'lulus') ? 'text-green-600' : 'text-red-600' ?>">
                    <?= ucfirst($attempt->exam_result) ?>
                </td>
                <td class="p-3 font-bold"><?= $attempt->exam_score ?>%</td>
                <td class="p-3">
                    <a href="<?= base_url('home/attemptDetails/' . $attempt->id_detail) ?>" 
                       class="bg-blue-500 text-yellow px-3 py-1 rounded">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
        </div>
    </div>  
</section>



<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Leaderboard</h2>
                <table class="w-full text-lg border-collapse table">
                    <thead class="table-purple text-white">
                        <tr>
                            <th class="p-3 text-left">Rank</th>
                            <th class="p-3 text-left">User </th>
                            <th class="p-3 text-right">Score</th>
                            <th class="p-3 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; foreach ($leaderboard as $user): ?>
                        <tr class="border-b hover:bg-purple-50 transition">
                            <td class="p-3 font-medium"><?= $rank++ ?></td>
                            <td class="p-3 flex items-center">
                                <img src="<?= base_url('images/' . ($user->foto ?: 'default.png')) ?>" alt="Profile Picture" width="35px" height="35px" class="w-10 h-10 rounded-full mr-3">
                                <span><?= $user->username ?></span>
                            </td>
                            <td class="p-3 text-right font-bold"><?= $user->exam_score ?>%</td>
                            <td class="p-3 text-right font-bold"><?= $user->created_at ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const examOpen = "<?= $child->exam_open ?>";
    const examClosed = "<?= $child->exam_closed ?>";
    
    if (examOpen !== "1969-12-31 18:00:00") {
        scheduleNotification(examOpen, "Ujian akan sudah dimulai, mohon refresh!");
    }
    
    if (examClosed !== "1969-12-31 18:00:00") {
        scheduleNotification(examClosed, "Ujian sudah ditutup.");
    }

    function scheduleNotification(time, message) {
        const examTime = new Date(time).getTime();
        const notifyTime = examTime - 1000; // 10 detik sebelum waktu ujian
        
        const now = new Date().getTime();
        const delay = notifyTime - now;

        if (delay > 0) {
            setTimeout(() => {
                if (confirm(message)) {
                    location.reload(); // Refresh setelah user klik OK
                } else {
                    location.reload(); // Refresh juga kalau user klik Cancel
                }
            }, delay);
        }
    }
});
</script>
