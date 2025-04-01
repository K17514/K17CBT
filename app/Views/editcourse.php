

<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container d-flex align-items-center">
        <form action="<?= base_url('home/simpancourse' ) ?>" method="post" class="d-flex align-items-center w-100 gap-2">
            <input type="hidden" name="id_course" value="<?= $child->id_course ?>">
            <input type="text" name="nama_course" value="<?= $child->nama_course ?>" class="editable-input flex-grow-1" placeholder="Your Course Name" required>
            <button type="submit" class="button custom-btn">Change</button>
        </form>
    </div>
</header>



<style>
    .editable-input {
        background: transparent;
        border: none;
        outline: none;
        color: #ffff;
        font-size: 30px;
        font-weight: bolder;
        width: auto;
    }
    
    .editable-input::placeholder {
        color: #dccce3;
    }
</style>

<?php 
    $uri = service('uri')->getSegment(2); // Get the second segment of the URI (e.g., 'about' in 'home/about')
?>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-lg-5 me-0" href="<?= base_url('home/index'); ?>">
            <img src="<?= base_url('images/chibitee-logo.png'); ?>" class="logo-image img-fluid" alt="templatemo pod talk">
        </a>

        <form action="#" method="get" class="custom-form search-form flex-fill me-3" role="search">
            <div class="input-group input-group-lg">
                <input name="search" type="search" class="form-control" id="search" placeholder="Search Exam"
                    aria-label="Search">
                <button type="submit" class="form-control" id="submit">
                    <i class="bi-search"></i>
                </button>
            </div>
        </form>


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
                    <a class="nav-link <?= ($uri == 'about') ? 'active' : '' ?>" href="<?= base_url('home/create'); ?>">Create</a>
                </li>


                <li class="nav-item dropdown pe-3 list-unstyled">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
             <img src="<?= base_url('images/' . (session()->get('foto') ?: 'default.png')) ?>" alt="" width="35px" height="35px" style="object-fit: cover; border-radius: 50%;">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= session()->get('username') ?? 'Guest' ?></span>
          </a>

          <?php
      if (session()->get('level')>0) {
        ?>
          <ul class="dropdown-menu dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= session()->get('username') ?></h6>
            </li>
            
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url ('home/profile')?>">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url ('home/profile')?>">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url ('home/about')?>">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
          </ul>
          <?php }
        ?>
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



