<?php 
    $uri = service('uri')->getSegment(2); // Get the second segment of the URI (e.g., 'about' in 'home/about')
?>


<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-lg-5 me-0" href="<?= base_url('home/index'); ?>">
            <img src="<?= base_url('images/' . ($webDetail['logo'] ?? 'default-logo.png')); ?>" class="logo-image img-fluid" alt="templatemo pod talk">
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

               



                <?php if (session()->get('level')== 49 || session()->get('level')== 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($uri == 'users') ? 'active' : '' ?>" href="<?= base_url('admin/tampiluser'); ?>">Users</a>
                    </li>

                    <?php if (session()->get('level')== 49) { ?>

                        <li class="nav-item">
                            <a class="nav-link <?= ($uri == 'users') ? 'active' : '' ?>" href="<?= base_url('admin/deleted_data'); ?>">Deleted Data</a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link <?= ($uri == 'users') ? 'active' : '' ?>" href="<?= base_url('admin/log_activity'); ?>">Logs</a>
                    </li>
                <?php } ?>


              




                <!-- ---------- FITUR PROFILE MENU ------------- -->
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
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('home/history/' . session()->get('id_user')) ?>">
                <i class="bi bi-gear"></i>
                <span>Exam History</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url ('home/courseview/'. session()->get('id_user'))?>">
                <i class="bi bi-gear"></i>
                <span>Made Courses</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
          </li>

          <?php if (session()->get('level') !== 49 && session()->get('level') !== 1) { ?>
              <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('home/user_log_activity') ?>">
                <i class="bi bi-gear"></i>
                <span>User Log</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
          </li>
        <?php } ?>
        <?php if (session()->get('level') == 49) { ?>
              <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/settings') ?>">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
          </li>
        <?php } ?>


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
 <!-- ---------------- END FITUR PROFILE MENU ------------ -->
    
            </ul>

            
            <div class="ms-4">
    <div id="google_translate_element"></div> <!-- Auto-Translate Dropdown -->
</div>
            <?php if (session()->get('level') < 1) { ?>
                <div class="ms-4">
                    <a href="<?= base_url('home/login'); ?>" class="btn custom-btn custom-border-btn">Get started</a>
                </div>
            <?php } else { ?>
                <div class="ms-4">
                    <a href="<?= base_url('home/logout'); ?>" class="btn custom-btn custom-border-btn">Log Out</a>
                </div>
            <?php } ?>
        </div>


        

    </div>
</nav>
