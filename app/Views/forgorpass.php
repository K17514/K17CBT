<section class="vh-100 d-flex align-items-center justify-content-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <!-- Login Title -->
        <div class="text-center mb-4">
          <h2 style="font-family: 'Poppins', sans-serif;">Password Recovery</h2>
          <p style="font-family: 'Poppins';">Enter your Email, we'll send the link to reset your password.</p>
        </div>

        <form action="<?=base_url('/home/forgot_password')?>" method="POST">
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" id="email" name="email" class="form-control form-control-lg" required />
            <label class="form-label" for="email">Email</label>
          </div>

          <!-- Submit button -->
          <div class="d-grid">
            <button type="submit" class="btn btn-lg text-white" style="background-color: mediumpurple;">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>