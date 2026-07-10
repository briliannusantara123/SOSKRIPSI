<?php $this->load->view('admin/layout/header') ?>

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
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-users"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Sub Category</a></li>
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
				                    <div class="card-header">
				                        <h4>Sub Category</h4>
				                        <!-- <button type="button" class="btn" style="background-color: #198754;border-radius: 20px;color: white;" data-bs-toggle="modal" data-bs-target="#exampleModaladdon">
												Add New User
										</button> -->
				                    </div>
				                    <div class="card-body table-border-style">
				                        <div class="table-responsive">
				                            <table class="table table-hover">
				                                <thead>
				                                    <tr>
				                                        <th>#</th>
				                                        <!-- <th>Image</th> -->
				                                        <th>Category Name</th>
				                                        <th>Show it on the Self Order</th>
				                                    </tr>
				                                </thead>
				                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td><?= $signature->description ?></td>
                                                        <td>
                                                            <?php if ($signature->sort == 0): ?>
                                                               <a href="<?= base_url() ?>index.php/Admin/UpdateSignature/<?= $signature->description ?>/last"><label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">First</label></a>
                                                            <?php else: ?>
                                                                <a href="<?= base_url() ?>index.php/Admin/UpdateSignature/<?= $signature->description ?>/first"><label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">Last</label></a>
                                                            <?php endif ?>
                                                        </td>
                                                    </tr>
				                                	<?php 
				                                	$no = 2;
				                                	foreach ($item as $d): ?>
					                                	<tr>
					                                        <td><?= $no++ ?></td>
					                                        <!-- <?php if ($d->image_path): ?>
					                                        	<td><a href="#" data-bs-toggle="modal" data-bs-target="#ubahgambar<?= $d->id?>"><img src="<?= $d->image_path ?>" style="width: 90px;height: 90px;border-radius: 20px;"></a></td>
					                                        <?php else: ?>	
					                                        	<td><a href="#" data-bs-toggle="modal" data-bs-target="#ubahgambar<?= $d->id?>"><img src="<?= base_url();?>assets/noimage.jpg" style="width: 90px;height: 90px;border-radius: 20px;"></a></td>
					                                        <?php endif ?> -->
					                                        <td><?= $d->sub_category ?></td>
					                                        <td>
					                                        	<?php if ($d->is_active_so == 1): ?>
					                                        		<a href="<?= base_url() ?>index.php/Admin/UpdateStatusCategory/<?= $d->sub_category ?>/inactive"><label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">Active</label></a>
					                                        	<?php else: ?>
					                                        		<a href="<?= base_url() ?>index.php/Admin/UpdateStatusCategory/<?= $d->sub_category ?>/active"><label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">Inactive</label></a>
					                                        	<?php endif ?>
					                                        </td>
					                                    </tr>
				                                	<?php endforeach ?>
				                                </tbody>
				                            </table>
				                        </div>
				                        <?= $links2 ?>
				                    </div>
				                </div>
				            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
</div>


<!-- <div class="modal fade" id="exampleModaladdon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"  style="border-radius: 20px;">
      <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
        <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Add New User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<form action="<?= base_url('index.php/admin/create_users') ?>" method="POST">
	        <div class="mb-3">
			  <label for="formFile" class="form-label">Username <span style="color: red;">*</span></label>
			  <input type="text" name="username" class="form-control" required="">
			</div>
			<div class="mb-3">
			  <label for="formFile" class="form-label">Password <span style="color: red;">*</span></label>
			  <input type="password" name="password" class="form-control" required="">
			</div>
			<div class="mb-3">
			  <label for="formFile" class="form-label">Role <span style="color: red;">*</span></label>
			  <select class="form-control" name="role" required="">
			  	<option value="admin">Admin</option>
			  	<option value="operation">Operation</option>
			  	<option value="marketing">Marketing</option>
			  </select>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn" style="background-color: #198754;color: white;">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div> -->

<!-- <?php foreach ($item as $o): ?>
    <div class="modal fade" id="exampleModaledituser<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/update_item/'.$o->id) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image</label>
                            <div class="row">
                                <div class="col-9">
                                    <br>    
                                    <input type="file" id="imageInput<?= $o->id ?>" name="image_path" class="form-control" accept="image/*">
                                </div>
                                <div class="col-3">
                                    <?php if ($o->image_path): ?>
                                        <img id="previewImage<?= $o->id ?>" src="<?= $o->image_path ?>" style="width: 100px; height: 100px; border-radius: 20px; float: right;">
                                        <a href="<?= base_url('index.php/admin/remove_image/'.$o->id) ?>" style="color: red">Remove Img</a>
                                    <?php else: ?>    
                                        <img id="previewImage<?= $o->id ?>" src="<?= base_url();?>assets/noimage.jpg" style="width: 90px; height: 90px; border-radius: 20px; float: right;">
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Description <span style="color: red;">*</span></label>
                            <input type="text" name="description" class="form-control" value="<?= $o->description ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Need Stock?</label>
                            <select class="form-control" name="need_stock">
                                <option value="1" <?= $o->need_stock == 1 ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= $o->need_stock == 0 ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Stock <span style="color: red;">*</span></label>
                            <input type="number" name="stock" class="form-control" value="<?= $o->stock ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Stock Status</label>
                            <select class="form-control" name="stock_status">
                                <option value="0" <?= $o->is_sold_out == 0 ? 'selected' : '' ?>>Available</option>
                                <option value="1" <?= $o->is_sold_out == 1 ? 'selected' : '' ?>>Sold Out</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="1" <?= $o->is_active == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $o->is_active == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #198754; color: white;">Submit</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageInput<?= $o->id ?>').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage<?= $o->id ?>').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
<?php endforeach ?> 

<?php foreach ($item as $o): ?>
    <div class="modal fade" id="ubahgambar<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Image</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/update_item/'.$o->id) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                        <div class="mb-3">
                            <div class="row">
                            	<div class="col-12">
                                    <?php if ($o->image_path): ?>
                                        <div class="image-wrapper">
					  						<img id="previewImageEdit<?= $o->id ?>" src="<?= $o->image_path ?>" alt="Image" class="responsive-image">
										</div>

                                        <a href="<?= base_url('index.php/admin/remove_image/'.$o->id) ?>" style="color: red;text-align: center;">Remove Img</a>
                                    <?php else: ?>    
                                        <img id="previewImageEdit<?= $o->id ?>" src="<?= base_url();?>assets/noimage.jpg" alt="Image" class="responsive-image">
                                    <?php endif ?>
                                </div>
                                <div class="col-12">
                                    <br>    
                                    <input type="file" id="imageInputEdit<?= $o->id ?>" name="image_path" class="form-control" accept="image/*">
                                </div>
                                
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #198754; color: white;">Submit</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageInputEdit<?= $o->id ?>').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImageEdit<?= $o->id ?>').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
<?php endforeach ?> -->

-->
   
 <?php $this->load->view('admin/layout/footer') ?>