<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $outlet; ?></title>
    <link href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url();?>assets/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .table-status {
            display: flex;
            height: 100%;
            
        }
        .table {
            border: 1px solid #ddd;
            width: 40%;
            margin: 2px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s;
        }
        .table.hover {
            transform: translateY(-2px);
        }
        .table-header {
            text-align: center;
            padding: 20px;
            font-size: 1.5em;
            position: relative;
            font-weight: bold;
        }
        .process {
            background-color: #ff4d4d;
            color: white;
            font-size: 30px;
        }

        .readytopickup {
            background-color: #ffc107;
            color: black;
            font-size: 30px;
        }
        .complete {
            background-color: #28a745;
            color: white;
            font-size: 30px;
        }
        .table-header .count {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            color: #003366;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 0.8em;
        }
        .order-list {
            overflow-y: auto;
            flex-grow: 1;
        }
        .order-item {
            margin: 10px 0;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 5px;
            background-color: #f1f1f1;
            font-size: 50px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .order-item:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="table-status">
        <div class="table">
            <div class="table-header process">
                PROCESS
                <div class="count">( <?= $process ?> )</div>
            </div>
            <div class="order-list">
             <div class="container text-center">
                <div class="row align-items-start">
                    <?php foreach ($table_process->result() as $tbl): ?>
                        <?php 
                        $cek = $this->model->cek(1, 1, $tbl->table_no)->num_rows(); 
                        $cekdessert = $this->model->cekdessert(1, 1, $tbl->table_no)->num_rows();
                        $cekorder = $two_letters = substr($tbl->table_no, 0, 2); 

                        ?>
                        <?php if ($cek > 0): ?>
                            <?php if ($cekorder == 'TA'): ?>
                                <div class="order-item">
                                    <?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert > 0 ? '*' : '' ?>
                                </div>
                             <?php else: ?>
                                <div class="order-item">
                                    T<?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert > 0 ? '*' : '' ?>
                                </div>
                             <?php endif ?>
                            
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
             </div>
            </div>
        </div>
        <div class="table">
            <div class="table-header readytopickup">
                READY TO PICKUP
                <div class="count">( <?= $pickup ?> )</div>
            </div>
            <div class="order-list">
             <div class="container text-center">
                <div class="row align-items-start">
                    <?php foreach ($table_pickup->result() as $tbl): ?>
                        <?php
                        $cek = $this->model->cekRP(1, 1, $tbl->table_no, $tbl->cekdata)->num_rows();
                        $cekitem = $this->model->cekitem($tbl->table_no, $tbl->cekdata)->result();
                        $cekdessert = $this->model->cekdessert(1, 1, $tbl->table_no, 'pickup')->num_rows();
                        $cekorder = substr($tbl->table_no, 0, 2);
                        ?>
                        <?php if ($cek > 0 && !$cekitem): ?>
                            <?php if ($cekorder == 'TA'): ?>
                                <div class="col">
                                    <div class="order-item"><?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert ? '*' : '' ?></div>
                                </div>
                             <?php else: ?>
                                 <div class="col">
                                    <div class="order-item">T<?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert ? '*' : '' ?></div>
                                </div>
                             <?php endif ?>
                            
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
             </div>
            </div>
        </div>
        <div class="table">
            <div class="table-header complete">
                COMPLETE
                <div class="count">( <?= $complete ?> )</div>
            </div>
            <div class="order-list">
                <div class="container text-center">
                    <div class="row align-items-start">
                        <?php foreach ($table_complete->result() as $tbl): ?>
                            <?php
                            $cek = $this->model->cekRP(1, 1, $tbl->table_no, $tbl->cekdata)->num_rows();
                            $cekitem = $this->model->cekitemcomplete($tbl->table_no, $tbl->cekdata)->result(); 
                            $cekdessert = $this->model->cekdessert(1, 1, $tbl->table_no, 'pickup')->num_rows();
                            $cekorder = substr($tbl->table_no, 0, 2);
                            ?>
                            <?php if ($cek > 0 && !$cekitem): ?>
                             <?php if ($cekorder == 'TA'): ?>
                                <div class="col">
                                    <div class="order-item"><?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert ? '*' : '' ?></div>
                                </div>
                             <?php else: ?>
                                 <div class="col">
                                    <div class="order-item">T<?= $tbl->table_no < 10 ? '0' . $tbl->table_no : $tbl->table_no ?>0<?= $tbl->cekdata ?><?= $cekdessert ? '*' : '' ?></div>
                                </div>
                             <?php endif ?>
                                
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function cycleTableHoverEffect() {
            const tables = document.querySelectorAll('.table');
            let currentIndex = 0;
            
            setInterval(() => {
                // Remove hover class from all tables
                tables.forEach(table => table.classList.remove('hover'));
                // Add hover class to the current table
                tables[currentIndex].classList.add('hover');
                // Update the current index
                currentIndex = (currentIndex + 1) % tables.length;
            }, 1000); // Change the interval time as needed
        }

        // Start the hover effect for the tables
        cycleTableHoverEffect();

        // Auto-refresh the page every 15 seconds
        setTimeout(() => {
            location.reload();
        }, 15000); // 15000 milliseconds = 15 seconds
    </script>
</body>
</html>
