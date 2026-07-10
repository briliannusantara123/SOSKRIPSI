<?php $this->load->view('admin/layout/header') ?>
<style type="text/css">
    .icon-container {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 25px; /* Sesuaikan dengan ukuran yang diinginkan */
            height: 25px; /* Sesuaikan dengan ukuran yang diinginkan */
            border-radius: 50%; /* Membuat lingkaran */
            background-color: <?= $color->color ?>; /* Warna biru */
            color: white; /* Warna ikon */
        }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Settings</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><div class=""><i class="feather icon-settings"></i></div></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Outlet</a></li>
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
                        <div style="margin-left: 20px;"> 
                            <h3><strong>Outlet Settings</strong></h3>
                        </div>  
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
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
                    <div class="card-body">
                        <div class="row">
                        	<div class="col-md-12">
                                <div class="card">
                                    <form action="<?= base_url() ?>index.php/admin/update_logo/<?= $logo->id ?>" method="POST" enctype="multipart/form-data">
                                        <div class="container" style="justify-content: center; align-items: center;margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-4">
                                                    <?php if ($logo->image_path): ?>
                                                        <img id="previewImage" src="<?= $logo->image_path ?>" style="width: 300px; height: 300px;border-radius: 30px;">
                                                    <?php else: ?>
                                                        <img id="previewImage" src="<?= base_url();?>assets/noimage.jpg" style="width: 300px; height: 300px;border-radius: 30px;">
                                                    <?php endif ?>        
                                                </div>
                                                <div class="col-8">
                                                      <div class="mb-3 row">
                                                        <h5 for="staticEmail" class="col-sm-2 col-form-label">Outlet Logo Path</h5>
                                                        <div class="col-sm-10">
                                                          <input type="file"id="imageInput" name="image_path" class="form-control"accept="image/*">
                                                        </div>
                                                      </div>
                                                      <div class="mb-3 row">
                                                        <h5 for="inputPassword" class="col-sm-2 col-form-label">Outlet Name</h5>
                                                        <div class="col-sm-10">
                                                          <input type="text"id="imageInput" name="title" class="form-control" value="<?= $logo->title ?>">
                                                        </div>
                                                      </div>
                                                      <div class="mb-3 row">
                                                        <h5 for="inputPassword" class="col-sm-4 col-form-label">Opening & Closing Hours</h5>
                                                        <div class="col-sm-3">
                                                          <input type="time"id="imageInput" name="open" class="form-control" value="<?= $logo->open ?>">
                                                        </div>
                                                        <h5 for="inputPassword" class="col-sm-1 col-form-label">-</h5>
                                                        <div class="col-sm-3">
                                                          <input type="time"id="imageInput" name="close" class="form-control" value="<?= $logo->close ?>">
                                                        </div>
                                                      </div>
                                                </div>
                                            </div>
                                            <button class="btn" style="float: right;margin-right: 20px;background-color: #198754;border-radius: 20px;color: white;margin-bottom: 10px;margin-top: 10px;">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
</div> -->
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
 <?php $this->load->view('admin/layout/footer') ?>