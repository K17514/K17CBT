<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Nilai</title>
  <style type="text/css">
    table,
    th,
    td {
      border-collapse: collapse;
      font-family: sans-serif;
      padding: 5px;
    }

    h1 {
      text-align: center;
    }
  </style>
</head>
<body>

<h3>Laporan Ujian</h3>
<p><strong>Exam:</strong> <?= $nama_exam ?></p>
<p><strong>Tanggal:</strong> <?= $tanggal_awal ?> - <?= $tanggal_akhir ?> </p>

    <tr>
      <td width="100px"><img src="<?= base_url('img/elysium-logo.png'); ?>" width="100px"></td>
      <td width="250%">COMPUTER BASED TEST CHIBI-TEE</td>
    </tr>
  </table>

  <h1>Laporan Nilai</h1>
  

  <!-- Data Table -->
  <table border="1" id="my-table">
    <thead>
      <tr>
        <th style="font-weight: bold;">No</th>
        <th style="font-weight: bold;">Username</th>
        <th style="font-weight: bold;">Nilai</th>
        <th style="font-weight: bold;">Status</th>
        <th style="font-weight: bold;">Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $ms = 1;
      foreach ($data as $key => $value) {
      ?>
        <tr>
          <td><?= $ms++ ?></td>
          <td><?= $value->username ?></td>
          <td><?= $value->exam_score ?></td>
          <td><?= $value->exam_result ?></td>
          <td><?= $value->date_of_exam ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

  <br>

</body>
</html>
