<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Profile</h2>
    </div>
</header>

<?php 
    $uri = service('uri')->getSegment(2); // Get the second segment of the URI (e.g., 'about' in 'home/about')
?>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-lg-5 me-0" href="<?= base_url('home/index'); ?>">
            <img src="<?= base_url('images/chibitee-logo.png'); ?>" class="logo-image img-fluid" alt="templatemo pod talk">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-lg-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($uri == 'index' || $uri == '' ) ? 'active' : '' ?>" href="<?= base_url('home/index'); ?>">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($uri == 'about') ? 'active' : '' ?>" href="<?= base_url('home/about'); ?>">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($uri == 'about') ? 'active' : '' ?>" href="<?= base_url('home/courseview/' . session()->get('id_user')); ?>">Create</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($uri == 'about') ? 'active' : '' ?>" href="<?= base_url('home/allcourseview'); ?>">Answer</a>
                </li>

    
            </ul>

            <?php if (session()->get('level') < 1) { ?>
                <div class="ms-4">
                    <a href="<?= base_url('home/login'); ?>" class="btn custom-btn custom-border-btn smoothscroll">Get started</a>
                </div>
            <?php } else { ?>
                <div class="ms-4">
                    <a href="<?= base_url('home/logout'); ?>" class="btn custom-btn custom-border-btn smoothscroll">Log Out</a>
                </div>
            <?php } ?>
        </div>
    </div>
</nav>


<section class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="profile-card text-center p-4 shadow-lg rounded bg-white">
                    <img src="<?= base_url('images/' . (session()->get('foto') ?: 'default.png')) ?>"   width="100px" height="100px" style="object-fit: cover; border-radius: 50%;">
                    <h2 class="mt-3"><?= session()->get('username')?></h2>
                    <p class="text-muted"><?= session()->get('email')?></p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Overview</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-profile">Edit Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#change-password">Change Password</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div class="tab-pane fade show active" id="overview">
                                <h5>Profile Details</h5>
                                <p><strong>Username:</strong> <?= session()->get('username')?></p>
                                <p><strong>Email:</strong> <?= session()->get('email') ?></p>
                            </div>



                            <div class="tab-pane fade" id="edit-profile">
                                <h5>Edit Profile</h5>
                  <form action="<?= base_url('home/update_profile') ?>" method="post" enctype="multipart/form-data">
                                    <?php if (session()->getFlashdata('successprofil')): ?>
                <div class="alert alert-success">
                  <?= session()->getFlashdata('successprofil') ?>
                </div>
              <?php endif; ?>
                                    <div class="mb-3 text-center">
                                        <img src="<?= base_url('images/' . (session()->get('foto') ?? 'default.png')) ?>" alt="Profile Picture" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Change Profile Picture</label>
                                        <input type="file" name="profile_image" id="profileImage" accept="images/" class="form-control-file">


                                    </div>
                                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?= session()->get('username')?>">
                      </div>
                    </div>
                                    <div class="d-flex">
                                        <a href="<?= base_url('home/delete_profile_picture') ?>" class="btn btn-danger me-4">Delete Picture</a>
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


                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newpassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                      <a href="<?= base_url('home/reset_pass/'.session()->get('id_user'))?>" class="btn btn-danger"></i>Reset Password</a>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
