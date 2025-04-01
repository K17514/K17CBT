<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
        camera-target="2.0 -1.5 2.0"> <!-- Moved camera down -->
      </model-viewer>
    </div>
    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
      <!-- Login Title -->
      <h2 class="text-center py-5" style="font-family: 'Poppins', sans-serif;">Register</h2>

<form action="<?=base_url('/home/aksi_register')?>" method="POST" onsubmit="return validatePassword()">
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="username" name="username" class="form-control form-control-lg" required />
    <label class="form-label" for="username">Username</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="email" id="email" name="email" class="form-control form-control-lg" required />
    <label class="form-label" for="email">Email address</label>
  </div>

  <!-- Password input -->
<div data-mdb-input-init class="form-outline mb-4 position-relative">
  <input type="password" id="pass" name="pass" class="form-control form-control-lg" required oninput="checkPasswordStrength()" />
  <label class="form-label" for="pass">Password</label>
  
  <!-- Ikon Mata untuk Tampilkan/Sembunyikan Password -->
  <span id="togglePassword" class="toggle-password">
  <i class="bi bi-eye"></i>
</span>


  <!-- Kotak Validasi Password -->
  <div class="password-checklist">
    <span id="length" class="check-item">8+ Karakter</span>
    <span id="uppercase" class="check-item">Huruf Besar</span>
    <span id="lowercase" class="check-item">Huruf Kecil</span>
    <span id="number" class="check-item">Angka</span>
    <span id="symbol" class="check-item">Simbol (@$!%*?&)</span>
  </div>
</div>

          
<button type="submit" data-mdb-button-init data-mdb-ripple-init style="background-color: mediumpurple;" class="btn btn-primary btn-lg btn-block">Register</button>
<a href="<?=base_url('/home/login') ?>" class="btn btn-primary btn-lg btn-block mx-4" style="background-color: mediumpurple;">Login</a>
</form>

<style>
  /* Modern Styling */
  .password-checklist {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 14px;
    margin-top: 5px;
    color: #888;
  }

  .check-item {
    padding: 3px 8px;
    border-radius: 5px;
    background: #f1f1f1;
    font-weight: 500;
    transition: 0.3s;
  }

  .check-item.valid {
    color: green;
    background: #e9ffe9;
  }

  .check-item.invalid {
    color: #888;
    background: #f1f1f1;
  }

  /* Toggle Password */
  .toggle-password {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    color: #888;
  }

  .toggle-password:hover {
    color: #333;
  }
</style>

<script>
  function checkPasswordStrength() {
    const password = document.getElementById('pass').value;
    
    updateCheck('length', password.length >= 8);
    updateCheck('uppercase', /[A-Z]/.test(password));
    updateCheck('lowercase', /[a-z]/.test(password));
    updateCheck('number', /\d/.test(password));
    updateCheck('symbol', /[@$!%*?&]/.test(password));
  }

  function updateCheck(id, condition) {
    const element = document.getElementById(id);
    if (condition) {
      element.classList.add('valid');
      element.classList.remove('invalid');
    } else {
      element.classList.add('invalid');
      element.classList.remove('valid');
    }
  }

  function validatePassword() {
    const password = document.getElementById('pass').value;
    const isValid = password.length >= 8 &&
                    /[A-Z]/.test(password) &&
                    /[a-z]/.test(password) &&
                    /\d/.test(password) &&
                    /[@$!%*?&]/.test(password);
    
    if (!isValid) {
      alert("Password belum memenuhi semua kriteria.");
      return false;
    }
    return true;
  }

  // Toggle Show/Hide Password
  document.getElementById('togglePassword').addEventListener('click', function () {
  const passwordField = document.getElementById('pass');
  const icon = this.querySelector('i');

  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash'); // Ubah ikon
  } else {
    passwordField.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye'); // Balikin ikon
  }
});

</script>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
