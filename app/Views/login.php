<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex align-items-center justify-content-center h-100">
      <div class="col-md-6 col-lg-7 col-xl-6">
        <model-viewer 
                id="3d" 
                style="width: 100%; max-width: 700px; height: 700px;"
                src="<?= base_url('images/cloud_station.glb')?>" 
                camera-controls 
                disable-zoom
                disable-pan
                auto-rotate
                autoplay ar
                exposure="1.2"
                shadow-intensity="1"
                environment-image="neutral"
                min-camera-orbit="-180deg 85deg auto"
                max-camera-orbit="180deg 85deg auto"
                min-field-of-view="30deg"
                max-field-of-view="150deg"
                camera-target="2.0 -1.5 2.0">
        </model-viewer>
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
        <h2 class="text-center py-5" style="font-family: 'Poppins', sans-serif;">Login</h2>

        <form action="<?=base_url('/home/aksi_login')?>" method="POST">
          <div class="form-outline mb-4">
            <input type="text" id="user" name="user" class="form-control form-control-lg" required />
            <label class="form-label" for="user">Username Or Email</label>
          </div>

          <div class="form-outline mb-4">
            <input type="password" id="pass" name="pass" class="form-control form-control-lg" required />
            <label class="form-label" for="pass">Password</label>
          </div>

          <div class="d-flex justify-content-around align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="remember" checked />
              <label class="form-check-label" for="remember"> Remember me </label>
            </div>
            <a href="<?=base_url('/home/forgorpass')?>">Forgot password?</a>
          </div>

          <!-- Google reCAPTCHA -->
          <div class="mb-4 text-center">
            <div class="g-recaptcha" data-sitekey="6LeZQekqAAAAAPiNKQ3qaP5Rr-UrphqwjW894Am2"></div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg btn-block" style="background-color: mediumpurple;">Log in</button>
          <a href="<?=base_url('/home/register') ?>" class="btn btn-primary btn-lg btn-block mx-4" style="background-color: mediumpurple;">Register</a>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Include reCAPTCHA API -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>