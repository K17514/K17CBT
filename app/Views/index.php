

        
        <section class="hero-section">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <div class="text-center mb-5 pb-2">
                            <h1 class="text-white">Computer Based Test</h1>

                            <p class="text-white">Easily create and answer multiple-choices questions</p>

                            <a href="<?= base_url('/home/allcourseview'); ?>" class="btn custom-btn  mt-3 me-4">Start Answering</a>
                           <a href="<?= base_url('/home/courseview/' . session()->get('id_user')); ?>" class="btn custom-btn mt-3">Start Creating</a>

                        </div>

                        <div class="owl-carousel owl-theme">
                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/chemistry.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Chemistry</h4>

                                    <span class="badge">Chemical Reactions</span>
                                    <span class="badge">Periodic Table Elements</span>
                                    <span class="badge">Organic Chemistry</span>
                                </div>

                                
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/computer.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Computer</h4>

                                    <span class="badge">Query</span>

                                    <span class="badge">Programming Basics</span>
                                </div>

                                
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/math.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Math</h4>

                                    <span class="badge">Algebra</span>

                                    <span class="badge">Geometry</span>

                                    <span class="badge">Basic Math</span>
                                </div>

                                
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/literature.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Literature</h4>

                                    <span class="badge">Poetry Analysis</span>

                                    <span class="badge">Classic Novels</span>
                                </div>

                               
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/language.jpg'); ?>" class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Language</h4>
                                    <span class="badge">Grammar</span>
                                    <span class="badge">Vocabulary</span>
                                </div>

                                
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/art.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">Art</h4>
                                    <span class="badge">Painting Technique</span>
                                    <span class="badge">Color Theory</span>
                                </div>

                                
                            </div>

                            <div class="owl-carousel-info-wrap item">
                                <img src="<?= base_url('images/profile/gen-ed.jpg'); ?>"
                                    class="owl-carousel-image img-fluid" alt="">

                                <div class="owl-carousel-info">
                                    <h4 class="mb-2">General Education</h4>
                                    <span class="badge">World History</span>
                                    <span class="badge">Logical</span>
                                </div>

                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
   <script>
let timeout, warningTimeout;

function resetTimer() {
    clearTimeout(timeout);
    clearTimeout(warningTimeout);

    // Set warning timeout (5 seconds before actual logout)
    warningTimeout = setTimeout(() => {
        const stayLoggedIn = confirm("You will be logged out in 5 seconds due to inactivity. Click OK to stay logged in.");
        if (stayLoggedIn) {
            resetTimer(); // Reset the timer if user wants to stay logged in
        }
    }, 10000); // Warning at 5 seconds

    // Set logout timeout (10 seconds total)
    timeout = setTimeout(() => {
        fetch("<?= base_url('home/logout') ?>") // Send logout request
            .then(() => {
                alert("Session expired due to inactivity.");
                window.location.href = "<?= base_url('home/login') ?>"; // Redirect to login
            });
    }, 120000); // 2 min inactivity
}

// Track user activity
document.addEventListener("mousemove", resetTimer);
document.addEventListener("keypress", resetTimer);
document.addEventListener("click", resetTimer);
document.addEventListener("scroll", resetTimer);

// Start tracking on page load
resetTimer();
</script>




       