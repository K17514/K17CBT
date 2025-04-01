<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Attempts Page</h2>
            </div>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
                <form action="<?= base_url('home/laporan_attempt') ?>" method="POST" target="_blank">
                    <h4 class="mb-3">Generate Report</h4>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="datetime-local" id="tanggal_awal" class="form-control" name="tanggal_awal">
                        </div>

                        <div class="col-md-3">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="datetime-local" id="tanggal_akhir" class="form-control" name="tanggal_akhir">
                        </div>

                        <div class="col-md-3 d-grid">
                            <button class="btn custom-btn">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>  
</section>

<?php 
$poinPerSoal = count($questions) > 0 ? round(100 / count($questions), 2) : 0; 
?>


<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
                <!-- Tombol Delete Selected -->
                <button id="delete-selected" class="btn btn-danger mb-3" disabled>
                    <i class="bi bi-trash"></i> Delete Selected
                </button>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th> <!-- Checkbox untuk Select All -->
                            <th>Username</th>
                            <th>Email</th>
                            <th>Date of Exam</th>
                            <th>Date of Submit</th>
                            <th>Time Taken</th>
                            <?php foreach ($questions as $q): ?>
                                <th>Q<?= $q->id_question ?></th>
                            <?php endforeach; ?>
                            <th>Total Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formatted_attempts as $attempt): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="select-attempt" value="<?= $attempt['id_detail'] ?>">
                                </td>
                                <td><?= $attempt['username'] ?></td>
                                <td><?= $attempt['email'] ?></td>
                                <td><?= $attempt['date_of_exam'] ?></td>
                                <td><?= $attempt['date_of_submit'] ?></td>
                                <td><?= $attempt['time_taken'] ?></td>

                                <?php 
                                    $total_questions = count($questions);
                                    $score_per_question = 100 / $total_questions;
                                ?>

                                <?php foreach ($questions as $q): ?>
                                    <td>
                                        <?php 
                                        $isCorrect = isset($attempt['questions'][$q->id_question]) ? $attempt['questions'][$q->id_question] : null;
                                        $isEssay = empty($q->right_option); // Soal essay jika tidak ada right_option

                                        if (is_null($isCorrect) && $isEssay): ?>
                                            <a href="<?= base_url('home/review_essay/' . $attempt['id_detail'] . '/' . $q->id_question) ?>" class="btn btn-sm btn-warning">Preview</a>
                                        <?php elseif (!is_null($isCorrect)): ?>
                                            <?= number_format($isCorrect == 1 ? $score_per_question : 0, 2) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>

                                <td><strong><?= number_format($attempt['exam_score'], 2) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
</section>

<!-- SweetAlert + JavaScript untuk Delete Multiple -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButton = document.getElementById("delete-selected");
        const checkboxes = document.querySelectorAll(".select-attempt");
        const selectAllCheckbox = document.getElementById("select-all");

        function updateDeleteButtonState() {
            const checkedCount = document.querySelectorAll(".select-attempt:checked").length;
            deleteButton.disabled = checkedCount === 0;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", updateDeleteButtonState);
        });

        selectAllCheckbox.addEventListener("change", function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateDeleteButtonState();
        });

        deleteButton.addEventListener("click", function () {
            let selectedIds = [];
            document.querySelectorAll(".select-attempt:checked").forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: "Are you sure?",
                text: "This will delete selected attempts permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete them!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?= base_url('home/killattempts') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            Swal.fire("Deleted!", "Selected attempts have been deleted.", "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Error!", "Failed to delete attempts.", "error");
                        }
                    }).catch(error => {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
                }
            });
        });
    });
</script>
