<section class="vh-100 d-flex align-items-center justify-content-center">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <!-- Status Message -->
        <div class="card shadow p-4">
          <h2 class="<?= $type === 'success' ? 'text-success' : 'text-danger' ?>" style="font-family: 'Poppins', sans-serif;">
            <?= $type === 'success' ? 'Success' : 'Error' ?>
          </h2>
          <p style="font-family: 'Poppins';"><?= $message; ?></p>

          <?php if ($type === 'success'): ?>
            <a href="<?= base_url('/home/login'); ?>" class="btn btn-success btn-lg">Go to Login</a>
          <?php else: ?>
            <a href="javascript:history.back()" class="btn btn-danger btn-lg">Try Again</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
