<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Log Activities</h2>
            </div>
        </div>
    </div>
</header>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column">
                <table id="logTable" class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Time</th>
                            <th>Activity</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($child as $log) : ?>
                            <tr>
                                <td><?= $log->username ?></td>
                                <td><?= $log->email ?></td>
                                <td><?= $log->happens_at ?></td>
                                <td><?= $log->what_happens ?></td>
                                <td><?= $log->ip_address ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>  
</section>
    </main>


    <footer class="site-footer">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <!-- Logo -->
            <div class="col-lg-2 col-md-3 col-12">
                <a class="navbar-brand" href="<?= base_url('home/index'); ?>">
                    <img src="<?= base_url('images/chibitee-logo.png'); ?>" class="logo-image img-fluid" alt="Chibi-Tee Logo">
                </a>
            </div>
<!-- Contact Info -->
            <div class="col-lg-3 col-md-4 col-12">
                <h6 class="site-footer-title mb-3">Contact</h6>
                <p class="mb-1"><strong>Phone:</strong> 0856-6849-9103</p>
                <p><strong>Email:</strong> <a href="mailto:kurumidafox@gmail.com">kurumidafox@gmail.com</a></p>
            </div>

            <!-- Copyright & Design Credit -->
            <div class="col-lg-3 col-md-5 col-12 text-md-end text-center">
                <p class="copyright-text mb-0">Copyright © 2036 Talk Pod Company</p>
                <p>Design: <a rel="nofollow" href="https://templatemo.com/page/1" target="_blank">TemplateMo</a></p>
                <p>Distribution: <a rel="nofollow" href="https://themewagon.com" target="_blank">ThemeWagon</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- DataTables CSS -->
<!-- jQuery (Load First) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS (Must be after jQuery) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#logTable').DataTable({
            "pageLength": 5, // Show only 5 rows at a time
            "lengthMenu": [5, 10, 25, 50], // Allow user to change row limit
            "ordering": true, // Enable column sorting
            "searching": true // Enable global search
        });

        // Add individual column search
        $('#logTable thead th').each(function () {
            var title = $(this).text();
            $(this).html(title + '<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search ' + title + '">');
        });

        // Apply column search
        table.columns().every(function () {
            var that = this;
            $('input', this.header()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
    });
</script>

</body>

</html>