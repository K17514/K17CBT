<?php
// Get search query and current page
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 5; // Show 5 exams per page



// If there's a search query, filter the exams; otherwise, use the full list
if (!empty($search)) {
    $filteredCourse = array_filter($child, function ($course) use ($search) {
        return stripos($course->nama_course, $search) !== false;
    });
} else {
    $filteredCourse = is_array($child) ? $child : [];
 // Show all exams when no search query
}

// Pagination logic
$totalCourse = count($filteredCourse);
$totalPages = ceil($totalCourse / $perPage);
$offset = ($page - 1) * $perPage;

// Slice array for pagination
$courseToShow = array_slice($filteredCourse, $offset, $perPage);

// $examsToShow = $filteredExams; // Show all exams
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Your Courses    <a href="<?= base_url('home/addcourse/') ?>" class="button custom-btn mb-5">+</a>
    </div></h2>
        
</header>

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
                <input name="search" type="search" class="form-control" id="search" placeholder="Search Course"
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



<section class="latest-podcast-section py-3 pb-0" id="section_2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">


                <!-- Exam List -->
        <ul class="list-group list-unstyled">
    <?php if (!empty($courseToShow)): ?>
        <?php foreach ($courseToShow as $course): ?>
            <li class="p-3 mb-3 position-relative">
                <div class="custom-block d-flex flex-column">
                    <div>
                        <h5 class="mb-2 text-uppercase d-flex align-items-center">
                            <a href="<?= base_url('home/listing/' . $course->id_course) ?>" class="text-decoration-none flex-grow-1">
                                <?= htmlspecialchars($course->nama_course) ?>
                            </a>
                            <!-- Tombol Hapus (X) -->
                            <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $course->id_course ?>">
                                &times;
                            </button>
                            <!-- Edit Button -->
                            <a href="<?= base_url('home/editcourse/' . $course->id_course) ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Detail Button (SweetAlert2) -->
                            <?php if (session()->get('level') == 49) { ?>
                        <a href="<?= base_url('admin/detailcourse/' . $course->id_course) ?>" class="btn btn-sm btn-info detail-btn">
                                <i class="bi bi-info-circle"></i>
                            </a>
                            <?php } ?>

                    </h5>
                        <p>Created by: <?= htmlspecialchars($course->username) ?></p>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="list-group-item text-center mb-5">No courses found. Click + to add a course.</li>
    <?php endif; ?>
</ul>


                <!-- Pagination -->
                 <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let id_course = this.getAttribute("data-id");

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
                    window.location.href = "<?= base_url('home/deleteCourse/') ?>/" + id_course;
                }
            });
        });
    });
});



</script>
