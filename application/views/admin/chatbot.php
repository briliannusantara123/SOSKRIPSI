<?php $this->load->view('admin/layout/header'); ?>

<div class="pcoded-main-container">
<div class="pcoded-content">

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">

            <div class="col-md-12">

                <div class="page-header-title">
                    <h5>
                        <i class="feather icon-message-circle"></i>
                        Riwayat Chatbot
                    </h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('index.php/Admin') ?>">
                            <i class="feather icon-home"></i>
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        Chatbot
                    </li>

                </ul>

            </div>

        </div>
    </div>
</div>


<div class="container-fluid">

    <!-- FILTER -->
    <div class="card mb-4 shadow-sm">

        <div class="card-body">

            <form method="get">

                <div class="row">

                    <div class="col-md-3">

                        <label>Dari Tanggal</label>

                        <input
                            type="date"
                            class="form-control"
                            name="date_from"
                            value="<?= $date_from ?>">

                    </div>

                    <div class="col-md-3">

                        <label>Sampai Tanggal</label>

                        <input
                            type="date"
                            class="form-control"
                            name="date_to"
                            value="<?= $date_to ?>">

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary btn-block">

                            <i class="feather icon-search"></i>

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="row">

        <?php if(empty($chatbot)){ ?>

            <div class="col-md-12">

                <div class="alert alert-warning">

                    Belum ada data chatbot.

                </div>

            </div>

        <?php } ?>


        <?php foreach($chatbot as $row){ ?>

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card shadow border-0 h-100 customer-card"
                 data-id="<?= $row->id_customer ?>"
                 style="cursor:pointer;transition:.3s;">

                <div class="card-body">

                    <div class="text-center">

                        <div style="
                            width:80px;
                            height:80px;
                            border-radius:50%;
                            background:#4e73df;
                            color:white;
                            margin:auto;
                            line-height:80px;
                            font-size:35px;">

                            <i class="feather icon-user"></i>

                        </div>

                        <br>

                        <h5>

                            Customer #<?= $row->customer_name ?>

                        </h5>

                        <span class="badge badge-success">

                            Meja <?= $row->table_id ?>

                        </span>

                    </div>

                    <hr>

                    <div class="mb-2">

                        <b>Chat Terakhir</b>

                        <p class="text-muted mb-1">

                            <?= character_limiter(strip_tags($row->customer_message),60) ?>

                        </p>

                    </div>

                </div>

                <div class="card-footer text-center bg-white">

                    <button
                        class="btn btn-primary btn-sm btn-block">

                        <i class="feather icon-eye"></i>

                        Lihat Percakapan

                    </button>

                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</div>
</div>

<style>

.customer-card:hover{

    transform:translateY(-5px);

    box-shadow:0 10px 30px rgba(0,0,0,.15)!important;

}

.customer-card{

    border-radius:15px;

}

</style>

<?php $this->load->view('admin/layout/footer'); ?>
