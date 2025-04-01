<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h3>Laporan Ujian</h3>
<p><strong>Exam:</strong> <?= $nama_exam ?></p>
<p><strong>Tanggal:</strong> <?= $tanggal_awal ?> - <?= $tanggal_akhir ?> </p>

<table>
    <thead>
        <tr>
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
                        <?php if (isset($attempt['questions'][$q->id_question])): ?>
                            <?php if ($attempt['questions'][$q->id_question] == 1): ?>
                                <?= number_format($score_per_question, 2) ?>
                            <?php else: ?>
                                 0.00
                            <?php endif; ?>
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

</body>
</html>
