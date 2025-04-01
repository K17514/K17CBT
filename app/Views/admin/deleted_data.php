<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Deleted Data</h2>
    </div>
</header>

<div class="container mt-4 pb-5">
    <div class="accordion" id="deletedDataAccordion">
        <?php 
        $tables = [
            'exam' => 'Exams',
            'course' => 'Courses',
            'user' => 'Users'
        ];

        $columns = [
            'exam' => ['nama_exam', 'deskripsi', 'deleted_at'],
            'course' => ['nama_course', 'deleted_at'],
            'user' => ['username', 'email', 'deleted_at']
        ];

        foreach ($tables as $key => $title) :  
            $data = $deletedData[$key] ?? []; 
        ?> 

        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?= $key ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $key ?>" aria-expanded="false">
                    <?= $title ?>
                </button>
            </h2>
            <div id="collapse<?= $key ?>" class="accordion-collapse collapse" data-bs-parent="#deletedDataAccordion">
                <div class="accordion-body">
                    <?php if (!empty($data) && is_array($data)): ?>
                        <form action="<?= base_url('admin/restore_all') ?>" method="POST" class="d-inline-block">
                            <input type="hidden" name="table" value="<?= $key ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Restore All</button>
                        </form>

                        <form action="<?= base_url('admin/kill_all') ?>" method="POST" class="d-inline-block">
                            <input type="hidden" name="table" value="<?= $key ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete all <?= $title ?> permanently?');">
                                MASSACRE
                            </button>
                        </form>


                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <?php foreach ($columns[$key] as $col) : ?>
                                        <th><?= ucfirst(str_replace('_', ' ', $col)) ?></th>
                                    <?php endforeach; ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row) : ?>
                                    <tr>
                                        <?php foreach ($columns[$key] as $col) : ?>
                                            <td><?= htmlspecialchars($row[$col] ?? '-') ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <form action="<?= base_url('admin/restore') ?>" method="POST" class="d-inline-block">
                                                <input type="hidden" name="table" value="<?= $key ?>">
                                                <input type="hidden" name="id" value="<?= $row[array_key_first($row)] ?? '' ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                            </form>

                                            <?php if ($key === 'exam'): ?>
                                                <form action="<?= base_url('admin/killexam/' . $row['id_exam']) ?>" method="POST" class="d-inline-block">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this permanently?');">
                                                        KILL
                                                    </button>
                                                </form>
                                            <?php elseif ($key === 'course'): ?>
                                                <form action="<?= base_url('admin/killcourse/' . $row['id_course']) ?>" method="POST" class="d-inline-block">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this permanently?');">
                                                        KILL
                                                    </button>
                                                </form>
                                            <?php elseif ($key === 'user'): ?>
                                                <form action="<?= base_url('admin/killuser/' . $row['id_user']) ?>" method="POST" class="d-inline-block">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user permanently?');">
                                                        KILL
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada data terhapus.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
