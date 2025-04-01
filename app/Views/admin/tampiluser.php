<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Users Page</h2>
            </div>
        </div>
    </div>
</header>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
                <div class="custom-block d-flex flex-column">
        <a href="<?= base_url('home/register')?>" class="btn btn-success">+ Tambah User</a>            
        <table class="table table-hover datatable">
    <thead>
      <tr>
        <th>No</th>
        <th>Username</th>
        <th>Email</th>
        <th>Level</th>
        <?php
      if (session()->get('level')==1 || session()->get('level')== 49) { 
        ?>
        <th>Aksi</th>
      <?php } ?>
      </tr>
    </thead>
    <tbody>
  <?php
  $ms = 1;
  foreach ($child as $key => $value) {
      // Convert numeric level to role name
      $role = ($value->level == 1) ? 'Admin' : 
              (($value->level == 2) ? 'User' : 
              (($value->level == 49) ? 'HACKER' : 'Unknown'));

      ?>
      <tr>
          <td><?= $ms++ ?></td>
          <td><?= $value->username ?></td>
          <td><?= $value->email ?></td>
          <td><?= $role ?></td> <!-- Show role name instead of number -->
          <?php if (session()->get('level') == 1 || session()->get('level') == 49) { ?>
              <td>
                  <a href="<?= base_url('admin/edit_user/'.$value->id_user)?>" class="btn btn-warning">
                      <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <button class="btn btn-danger delete-btn" data-id="<?= $value->id_user ?>">
                  <i class="bi bi-trash"></i>  Hapus
                  </button>
              </td>
          <?php } ?>
      </tr>
      <?php
  }
  ?>
</tbody>

  </table>
</div>
</div>
</div>  
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let id_user = this.getAttribute("data-id");

            Swal.fire({
                title: "Are you sure?",
                text: "This will delete the course and all related exams, questions, and options!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('admin/hapus_user/') ?>/" + id_user;
                }
            });
        });
    });
});



</script>