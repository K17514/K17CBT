<?= $this->include('header') ?>
<?= $this->include('menu') ?>

<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Review Essay</h2>
            </div>
        </div>
    </div>
</header>
<div class="container mt-4">

    <form action="<?= base_url('home/submit_essay_correction') ?>" method="POST">
        <input type="hidden" name="id_detail" value="<?= $attempt->id_detail ?>">

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Correct?</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $q) : ?>
                    <tr>
                        <td><?= $q->question ?></td>
                        <td><?= isset($answerMap[$q->id_question]) ? $answerMap[$q->id_question] : '-' ?></td>
                        <td>
                            <label class="mr-3">
                                <input type="radio" name="score[<?= $q->id_question ?>]" value="1"> ✔
                            </label>
                            <label>
                                <input type="radio" name="score[<?= $q->id_question ?>]" value="0"> ❌
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Submit Score</button>
    </form>
</div>

<?= $this->include('footer') ?>
