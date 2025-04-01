<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Your Play History</h2>
            </div>
        </div>
    </div>
</header>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
                <div class="custom-block d-flex flex-column">
                    
        <table class="table table-hover datatable">
    <thead>
      <tr>
        <th>No</th>
        <th>Exam Name</th>
        <th>Score</th>
        <th>Date Played</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $ms=1;
      foreach ($child as $key => $value) {
        ?>
        <tr>
          <td><?= $ms++ ?></td>
          <td><?= $value->nama_exam ?></td>
          <td><?= $value->exam_score ?></td>
          <td><?= $value->created_at ?></td>
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