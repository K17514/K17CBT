<section class="vh-100 d-flex align-items-center justify-content-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <!-- Reset Password Title -->
        <div class="text-center mb-4">
          <h2 style="font-family: 'Poppins', sans-serif;">Reset Password</h2>
          <p style="font-family: 'Poppins';">Enter your new password below.</p>
        </div>

        <form action="<?= base_url('/home/update_password?token=' . $token) ?>" method="POST" onsubmit="return validatePassword()">
          <!-- New Password input -->
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

          <!-- Confirm Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-lg" required />
            <label class="form-label" for="confirm_password">Confirm Password</label>
          </div>

          <!-- Submit button -->
          <div class="d-grid">
            <button type="submit" class="btn btn-lg text-white" style="background-color: mediumpurple;">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
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
