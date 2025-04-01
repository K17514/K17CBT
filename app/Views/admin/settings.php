<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Web Settings</h2>
    </div>
</header>

<?php 
    $uri = service('uri')->getSegment(2); // Get the second segment of the URI (e.g., 'about' in 'home/about')
?>



<section class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-profile">Edit Web Identity</button>
                            </li>
                        </ul>


                            <div class="tab-pane fade show active" id="edit-profile">

                  <form action="<?= base_url('admin/update_setting') ?>" method="post" enctype="multipart/form-data">
                                    <?php if (session()->getFlashdata('successprofil')): ?>
                <div class="alert alert-success">
                  <?= session()->getFlashdata('successprofil') ?>
                </div>
              <?php endif; ?>
                                    <div class="mt-3 mb-3 text-center">
                                        <img src="<?= base_url('images/' . ($child->logo ?? 'default.png')) ?>" alt="Profile Picture" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Change Logo</label>
                                        <input type="file" name="profile_image" id="profileImage" accept="images/" class="form-control-file">


                                    </div>
                                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Web Title</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?= $child->title?>">
                      </div>
                    </div>
                                    <div class="d-flex">
                                    
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>




                            <div class="tab-pane fade pt-3" id="change-password">
                  <!-- Change Password Form -->
                  <form action="<?= base_url('home/change_pass') ?>" method="post">

                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                  <?= session()->getFlashdata('success') ?>
                </div>
              <?php endif; ?>


    
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
