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
                            <li class="breadcrumb-item"><a href="#!">Assign Sub Category</a></li>
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
				                        <h4>Assign Sub Category</h4>
                                        <form method="get" action="<?= base_url('index.php/admin/asigncategory'); ?>">
                                            <label>Item Name</label>
                                            <select name="item_name" class="form-control" id="item_name">
                                                <option value="">All Item</option>
                                                <?php foreach ($dataitem as $s): ?>
                                                    <option value="<?= $s->description ?>" data-sub-category="<?= $s->sub_category ?>" <?= $item_name == $s->description ? 'selected' : '' ?>><?= $s->description ?></option>
                                                <?php endforeach ?>
                                            </select>

                                            <label>Sub Category 1</label>
                                            <select name="sub" class="form-control" id="sub_category">
                                                <option value="">All Categories</option>
                                                <?php foreach ($sub as $s): ?>
                                                    <option value="<?= $s->sub_category ?>" <?= $subc == $s->sub_category ? 'selected' : '' ?>><?= $s->sub_category ?></option>
                                                <?php endforeach ?>
                                            </select>

                                            <label>Sub Category 2</label>
                                            <select name="subso" class="form-control">
                                                <option value="">All Categories</option>
                                                <?php foreach ($subso as $s): ?>
                                                    <option value="<?= $s->description ?>" <?= $subcso == $s->description ? 'selected' : '' ?>><?= $s->description ?></option>
                                                <?php endforeach ?>
                                            </select>

                                            <label>Signature</label>
                                            <select name="signature" class="form-control">
                                                <option value="all" <?= $signature === 'all' ? 'selected' : '' ?>>All</option>
                                                <option value="1" <?= $signature === '1' ? 'selected' : '' ?>>Yes</option>
                                                <option value="0" <?= $signature === '0' ? 'selected' : '' ?>>No</option>
                                            </select>

                                            <button type="submit" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;float: right;">Search</button>
                                        </form>
				                    </div>
				                    <div class="card-body table-border-style">
                                        <button type="button" class="btn" style="background-color: #198754;border-radius: 20px;color: white;margin-bottom: 10px" data-bs-toggle="modal" data-bs-target="#exampleModalAsign">
                                                Assign Sub Category
                                        </button>
				                        <div class="table-responsive">
				                            <table class="table table-hover">
				                                <thead>
				                                    <tr>
				                                        <th>#</th>
				                                        <th>Image</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
				                                        <th>Sub Category 1</th>
                                                        <th>Sub Category 2</th>
				                                        <th>Signature</th>
                                                        <th>Action</th>
				                                    </tr>
				                                </thead>
				                                <tbody>
				                                	<?php 
				                                	$no = 1;
				                                	foreach ($item as $d): ?>
					                                	<tr>
					                                        <td><?= $no++ ?></td>
					                                        <?php if ($d->image_path): ?>
					                                        	<td><a href="#" data-bs-toggle="modal" data-bs-target="#ubahgambar<?= $d->id?>"><img src="<?= $d->image_path ?>" style="width: 90px;height: 90px;border-radius: 20px;"></a></td>
					                                        <?php else: ?>	
					                                        	<td><a href="#" data-bs-toggle="modal" data-bs-target="#ubahgambar<?= $d->id?>"><img src="<?= base_url();?>assets/noimage.jpg" style="width: 90px;height: 90px;border-radius: 20px;"></a></td>
					                                        <?php endif ?>
                                                            <td><?= $d->no ?></td>
                                                            <td><?= $d->description ?></td>
					                                        <td><?= $d->sub_category ?></td>
                                                            <td><?= $d->sub_category_so ?></td>
					                                        <td>
					                                        	<?php if ($d->chef_recommended == 1): ?>
					                                        		<a href="<?= base_url() ?>index.php/Admin/Signature/<?= $d->id ?>/no"><label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">Yes</label></a>
					                                        	<?php else: ?>
					                                        		<a href="<?= base_url() ?>index.php/Admin/Signature/<?= $d->id ?>/yes"><label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">No</label></a>
					                                        	<?php endif ?>
					                                        </td>
                                                            <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModaledit<?= $d->id?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a>
                                                                <a href="<?= base_url() ?>index.php/Admin/asigndelete/<?= $d->id ?>"><i class="fas fa-trash" style="color: red;font-size: 20px;"></i></a>
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


<div class="modal fade" id="exampleModalAsign" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"  style="border-radius: 20px;">
      <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
        <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Asign Sub Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<form action="<?= base_url('index.php/admin/asignpost') ?>" method="POST">
	        <div class="mb-3">
			  <label for="formFile" class="form-label">Item Name<span style="color: red;">*</span></label>
			  <select name="item_code" class="form-control" id="item_namemodal" required="">
                    <option value="">Select Item</option>
                    <?php foreach ($dataitem as $s): ?>
                        <option value="<?= $s->no ?>" data-sub-category="<?= $s->sub_category ?>" <?= $item_name == $s->description ? 'selected' : '' ?>><?= $s->description ?></option>
                    <?php endforeach ?>
                </select>
			</div>
			<div class="mb-3">
			  <label for="formFile" class="form-label">Sub Category 1 <span style="color: red;">*</span></label>
                <select name="sub" class="form-control" id="sub_categorymodal" required="">
                    <option value="" selected="" disabled="">Select Sub Category 1</option>
                    <?php foreach ($sub as $s): ?>
                        <option value="<?= $s->sub_category ?>" <?= $subc == $s->sub_category ? 'selected' : '' ?>><?= $s->sub_category ?></option>
                    <?php endforeach ?>
                </select>
			</div>
			<div class="mb-3">
                <label for="formFile" class="form-label">Sub Category 2 <span style="color: red;">*</span></label>
                <select name="subso" id="subso" class="form-control" required="">
                    <option value="" selected="" disabled="">Select Sub Category 2</option>
                    <?php foreach ($subso as $s): ?>
                        <option value="<?= $s->description ?>" <?= $subc == $s->description ? 'selected' : '' ?>><?= $s->description ?></option>
                    <?php endforeach ?>
                    <!-- <option value="custom">Custom</option> -->
                </select>
                <!-- <input type="text" name="custom_subso" id="customSubso" class="form-control mt-2" placeholder="Input custom category" style="display:none;" /> -->
            </div>

            <div class="mb-3">
              <label for="formFile" class="form-label">Signature <span style="color: red;">*</span></label>
              <select class="form-control" name="signature" required="">
                <option value="1">Yes</option>
                <option value="0">No</option>
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
</div>

<?php foreach ($item as $o): ?>
    <div class="modal fade" id="exampleModaledit<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Assign Sub Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/asignpost/update') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Item Name<span style="color: red;">*</span></label>
                            <input type="text" value="<?= $o->description ?>" class="form-control" readonly>
                            <input type="hidden" name="item_code" value="<?= $o->no ?>" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                          <label for="formFile" class="form-label">Sub Category 1 <span style="color: red;">*</span></label>
                            <select name="sub" class="form-control" id="sub_categorymodal" required="">
                                <?php foreach ($sub as $s): ?>
                                    <option value="<?= $s->sub_category ?>" <?= $o->sub_category == $s->sub_category ? 'selected' : '' ?>><?= $s->sub_category ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Sub Category 2 <span style="color: red;">*</span></label>
                            <select name="subso" id="subsoedit" class="form-control" required="">
                                <option selected="" disabled="">Select sub category 2</option>
                                <?php foreach ($subso as $s): ?>
                                    <option value="<?= $s->description ?>" <?= $o->sub_category_so == $s->description ? 'selected' : '' ?>><?= $s->description ?></option>
                                <?php endforeach ?>
                                <!-- <option value="custom">Custom</option> -->
                            </select>
                           <!--  <input type="text" name="custom_subso" id="customSubsoedit" class="form-control mt-2" placeholder="Input custom category" style="display:none;" /> -->
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Signature</label>
                            <select class="form-control" name="signature">
                                <option value="1" <?= $o->chef_recommended == 1 ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= $o->chef_recommended == 0 ? 'selected' : '' ?>>No</option>
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
<?php endforeach ?> 

-->
   
 <?php $this->load->view('admin/layout/footer') ?>

 <script>
    // document.getElementById('subso').addEventListener('change', function () {
    //     var customInput = document.getElementById('customSubso');
    //     if (this.value === 'custom') {
    //         customInput.style.display = 'block';
    //     } else {
    //         customInput.style.display = 'none';
    //     }
    // });
    // document.getElementById('subsoedit').addEventListener('change', function () {
    //     var customInput = document.getElementById('customSubsoedit');
    //     if (this.value === 'custom') {
    //         customInput.style.display = 'block'; 
    //     } else {
    //         customInput.style.display = 'none'; 
    //     }
    // });
    // Ambil elemen item_name dan sub_category
    const itemNameSelect = document.getElementById('item_name');
    const subCategorySelect = document.getElementById('sub_category');
    const itemNameSelectmodal = document.getElementById('item_namemodal');
    const subCategorySelectmodal = document.getElementById('sub_categorymodal');

    // Fungsi untuk memperbarui sub_category berdasarkan item yang dipilih
    itemNameSelect.addEventListener('change', function() {
        // Ambil sub_category dari option yang dipilih
        const selectedItem = itemNameSelect.options[itemNameSelect.selectedIndex];
        const subCategoryValue = selectedItem.getAttribute('data-sub-category');

        // Update nilai sub_category select
        if (subCategoryValue) {
            subCategorySelect.value = subCategoryValue;
        } else {
            subCategorySelect.value = ""; // Reset jika tidak ada sub_category yang terkait
        }
    });

    itemNameSelectmodal.addEventListener('change', function() {
        // Ambil sub_category dari option yang dipilih
        const selectedItemmodal = itemNameSelectmodal.options[itemNameSelectmodal.selectedIndex];
        const subCategoryValuemodal = selectedItemmodal.getAttribute('data-sub-category');

        // Update nilai sub_category select
        if (subCategoryValuemodal) {
            subCategorySelectmodal.value = subCategoryValuemodal;
        } else {
            subCategorySelectmodal.value = ""; // Reset jika tidak ada sub_category yang terkait
        }
    });

    // Jika item_name sudah dipilih sebelumnya, set sub_category sesuai dengan item_name yang terpilih
    (function() {
        const selectedItem = itemNameSelect.querySelector('option:checked');
        if (selectedItem) {
            const subCategoryValue = selectedItem.getAttribute('data-sub-category');
            if (subCategoryValue) {
                subCategorySelect.value = subCategoryValue;
            }
        }
    })();

    (function() {
        const selectedItemmodal = itemNameSelectmodal.querySelector('option:checked');
        if (selectedItemmodal) {
            const subCategoryValuemodal = selectedItemmodal.getAttribute('data-sub-category');
            if (subCategoryValuemodal) {
                subCategorySelectmodal.value = subCategoryValuemodal;
            }
        }
    })();
</script>