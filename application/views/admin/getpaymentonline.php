<?php $this->load->view('admin/layout/header') ?>

<!-- jQuery (cukup sekali saja) -->
<!-- Bootstrap CSS -->
<link href="<?= base_url('assets/library/bootstrap-5/bootstrap.min.css'); ?>" rel="stylesheet" />


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Bundle (sudah include Popper) -->
<script src="<?= base_url('assets/library/bootstrap-5/bootstrap.bundle.min.js'); ?>"></script>

<!-- dselect JS -->
<script src="<?= base_url('assets/library/dselect.js'); ?>"></script>



<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Master Data</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-file-text"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Get Payment Online</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icFon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        	<div class="col-md-12">
				                <div class="card">
				                    <div class="card-header">
									    <form method="get" action="<?= base_url('index.php/admin/get_payment_online'); ?>" class="row g-2 align-items-center justify-content-end">
									        
									        <div class="col-md-3">
									            <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" value="<?= $customer_name ?>">
									        </div>

									        <div class="col-md-3">
									            <input type="text" name="table_number" class="form-control" placeholder="Table Number" value="<?= $table_number ?>">
									        </div>

									        <div class="col-auto">
									            <button type="submit" class="btn btn-success" style="border-radius: 20px;">
									                <i class="feather icon-search"></i> Search
									            </button>
									        </div>

									    </form>
									</div>

				                    <div class="card-body table-border-style">
				                     <?php if ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'operation'): ?>
						                <div class="col-xl-12 col-md-12" >
						                    <div class="card table-card" style="border-radius: 10px;">
						                        <div class="card-header" style="background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);;border-radius: 10px;">
						                            <h4 style="color: white;">Get Payment Online</h4>
						                            <div class="card-header-right">
						                                <div class="btn-group card-option">
						                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						                                        <i class="feather icon-more-horizontal" style="color: white;"></i>
						                                    </button>
						                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
						                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
						                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
						                                        <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
						                                        <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
						                                    </ul>
						                                </div>
						                            </div>
						                        </div>
						                        <div class="card-body p-0">
						                            <div class="table-responsive">
						                                <table class="table table-hover mb-0">
						                                    <thead>
						                                        <tr>
						                                        	<th>External ID</th>
						                                            <th>
						                                                Customer Name
						                                            </th>
						                                            <th>Table Number</th>
						                                            <th class="text-right">Action</th>
						                                        </tr>
						                                    </thead>
						                                    <tbody>
						                                        <?php foreach ($pay as $e): 
						                                            
						                                            ?>
						                                            <tr>
						                                                <td>
						                                                    <?= $e->external_id_so ?>
						                                                </td>
						                                               <td><?= $e->customer_name ?></td>
						                                               <td><?= $e->id_table ?></td>
						                                                <td>
						                                                	<a href="<?= base_url() ?>index.php/Admin/get_payment_api/<?= $e->external_id_so ?>/<?= $e->id_customer ?>" class="btn btn-success" style="width: 100%">Get Payment</a>
						                                                </td>
						                                            </tr>
						                                        <?php endforeach; ?>
						                                    </tbody>
						                                </table>
						                            </div>
						                        </div>
						                        <br>
						                    </div>
						                </div>
						             <?php endif ?>
				                    </div>
				                </div>
				            </div>
				            <?= $links2 ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    const select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, { search: true });
});
</script>


   
 <?php $this->load->view('admin/layout/footer') ?>