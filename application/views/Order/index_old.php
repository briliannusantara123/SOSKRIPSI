<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="refresh" content="15">
  <title><?= $outlet; ?></title>
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/daterangepicker/daterangepicker.css">
  <script src="<?= base_url('assets'); ?>/plugins/jquery/jquery.min.js"></script>
  <style>
    /* CSS untuk label di tengah */
    .center-label {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      width: 100%;
      text-align: center;
    }
  </style>
</head>
<body style="background-color: darkgrey;">
  <div class="row">
    <div id="scrollContainer" style="overflow-x: hidden; overflow-y: hidden; max-width: 1350px; font-family: Tahoma, Verdana, Courier, Helvetica;">
      <div id="innerContainer" style="display: inline-block;">
        <table>
          <tr>
             <?php if ($table_order1->num_rows() > 0) { ?> 
              <?php foreach ($table_orderReady->result() as $tbl) {
                $detail = $this->model->detailList(1, $tipe, $tbl->id, $tbl->table_no)->result();
                $detailready = $this->model->detailListReady(1, $tipe, $tbl->id, $tbl->table_no)->result();
                $remaining = $this->model->remainingList(1, $tipe, $tbl->id, $tbl->table_no)->row();
              ?>
                <td style="vertical-align: text-top; min-height: 150px; max-height: 155px; overflow-y: hidden;">
                  <div class="col-lg-5 col-7" style="vertical-align: text-top; height: 635px; border-style: solid; border-color: navy; overflow-y: hidden; border-radius: 25px; background-color: navy">
                    <table style="width: 318px; font-size: 18px; font-weight: bold;">
                      <tr>
                        <td width="65%" style="font-size: 32px; font-weight: bold; color: white; background: linear-gradient(to top, #33ccff 0%, #000066 100%)">&nbsp; Table <?= $tbl->table_no ?></td>
                        <td width="35%" align="right" style="font-size: 26px; font-weight: bold; color: white; background: linear-gradient(to top, #33ccff 0%, #000066 100%)">(<?= $remaining->total_items; ?>) &nbsp;</td>
                      </tr>
                    </table>
                    <div class="col-lg-8 col-12" style="vertical-align: text-top; min-height: 610px; max-height: 620px; overflow-y: scroll; background-color: white;">
                    <?php 
                    $cek = $this->model->cek(1,1,$tbl->table_no)->num_rows();
                    if ($cek > 0): ?>
                    	<div class="center-label" style="background-color: red; font-size: 25px; font-weight: bold;">PROCESS</div>
                    <?php endif ?>
                      
                      <!-- Table for PROCESS -->
                      <table style="min-width: 288px; max-width: 298px; font-size: 25px; font-weight: bold;">
                        <?php
                        $cat = "";
                        foreach ($detail as $dtl) {
                          $category = $this->model->categoryList(1, $dtl->cat)->row();
                        ?>
                          <?php if ($cat != $category->description) { ?>
                            <tr>
                              <td width="100%" colspan="2" style="vertical-align: text-top; background-color: <?= $category->color_set; ?>"> <?= $category->description; ?></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td width="15%" style="vertical-align: text-top;"> (<?= $dtl->qty; ?>)</td>
                            <td width="85%"><?= $dtl->menu; ?></td>
                          </tr>
                        <?php $cat = $category->description;
                        } ?>
                      </table>
                      <div class="center-label" style="background-color: green; font-size: 25px; font-weight: bold;">READY TO PICKUP</div>
                      <!-- Table for READY TO PICKUP -->
                      <table style="min-width: 288px; max-width: 298px; font-size: 25px; font-weight: bold;">
                        <?php
                        $cat = "";
                        foreach ($detailready as $dtl) {
                          $category = $this->model->categoryList(1, $dtl->cat)->row();
                        ?>
                          <?php if ($cat != $category->description) { ?>
                            <tr>
                              <td width="100%" colspan="2" style="vertical-align: text-top; background-color: <?= $category->color_set; ?>"> <?= $category->description; ?></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td width="15%" style="vertical-align: text-top;"> (<?= $dtl->qty; ?>)</td>
                            <td width="85%"><?= $dtl->menu; ?></td>
                          </tr>
                        <?php $cat = $category->description;
                        } ?>
                      </table>
                    </div>
                  </div>
                </td>
              <?php } ?>
             <?php } else{?>
              <!-- Loop for table_orderReady -->
              <?php foreach ($table_orderReady->result() as $tbl) {
                $detail = $this->model->detailList(1, $tipe, $tbl->id, $tbl->table_no)->result();
                $detailready = $this->model->detailListReady(1, $tipe, $tbl->id, $tbl->table_no)->result();
                $remaining = $this->model->remainingList(1, $tipe, $tbl->id, $tbl->table_no)->row();
              ?>
                <td style="vertical-align: text-top; min-height: 150px; max-height: 155px; overflow-y: hidden;">
                  <div class="col-lg-5 col-7" style="vertical-align: text-top; height: 635px; border-style: solid; border-color: navy; overflow-y: hidden; border-radius: 25px; background-color: navy">
                    <table style="width: 318px; font-size: 18px; font-weight: bold;">
                      <tr>
                        <td width="65%" style="font-size: 32px; font-weight: bold; color: white; background: linear-gradient(to top, #33ccff 0%, #000066 100%)">&nbsp; Table <?= $tbl->table_no ?></td>
                        <td width="35%" align="right" style="font-size: 26px; font-weight: bold; color: white; background: linear-gradient(to top, #33ccff 0%, #000066 100%)">(<?= $remaining->items; ?>) &nbsp;</td>
                      </tr>
                    </table>
                    <div class="col-lg-8 col-12" style="vertical-align: text-top; min-height: 610px; max-height: 620px; overflow-y: scroll; background-color: white;">
                      
                      <!-- Table for PROCESS -->
                      <table style="min-width: 288px; max-width: 298px; font-size: 25px; font-weight: bold;">
                        <?php
                        $cat = "";
                        foreach ($detail as $dtl) {
                          $category = $this->model->categoryList(1, $dtl->cat)->row();
                        ?>
                          <?php if ($cat != $category->description) { ?>
                            <tr>
                              <td width="100%" colspan="2" style="vertical-align: text-top; background-color: <?= $category->color_set; ?>"> <?= $category->description; ?></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td width="15%" style="vertical-align: text-top;"> (<?= $dtl->qty; ?>)</td>
                            <td width="85%"><?= $dtl->menu; ?></td>
                          </tr>
                        <?php $cat = $category->description;
                        } ?>
                      </table>
                      <div class="center-label" style="background-color: green; font-size: 25px; font-weight: bold;">READY TO PICKUP</div>
                      <!-- Table for READY TO PICKUP -->
                      <table style="min-width: 288px; max-width: 298px; font-size: 25px; font-weight: bold;">
                        <?php
                        $cat = "";
                        foreach ($detailready as $dtl) {
                          $category = $this->model->categoryList(1, $dtl->cat)->row();
                        ?>
                          <?php if ($cat != $category->description) { ?>
                            <tr>
                              <td width="100%" colspan="2" style="vertical-align: text-top; background-color: <?= $category->color_set; ?>"> <?= $category->description; ?></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td width="15%" style="vertical-align: text-top;"> (<?= $dtl->qty; ?>)</td>
                            <td width="85%"><?= $dtl->menu; ?></td>
                          </tr>
                        <?php $cat = $category->description;
                        } ?>
                      </table>
                    </div>
                  </div>
                </td>
              <?php } ?>
            <?php } ?>

          </tr>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Function to scroll container to the left
    function scrollLeft() {
      var scrollContainer = document.getElementById('scrollContainer');
      scrollContainer.scrollLeft -= 60; // Adjust scrolling speed as needed
    }

    // Function to scroll container to the right
    function scrollRight() {
      var scrollContainer = document.getElementById('scrollContainer');
      scrollContainer.scrollLeft += 60; // Adjust scrolling speed as needed
    }

    // Automatically scroll to the right and left every few seconds
    setInterval(scrollRight, 2000); // Adjust the interval as needed
    // setInterval(scrollLeft, 2000); // Adjust the interval as needed
  </script>
</body>
</html>
