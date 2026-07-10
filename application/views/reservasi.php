<?php $this->load->view('template/headmenu') ?>
<style>
    body {
        background-color: #f3f3f3;
        font-family: 'Arial', sans-serif;
        margin: 0;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 4;
    }    

</style>
</head>
<body>
    <header>
        <div>
                <h2 style="margin: 0; margin-left: 5px;"><strong>Cutomers Reservations</strong></h2>
        </div>
    </header>
	<section class="vh-100" style="margin-top: 120px;">
		<div class="container-fluid h-custom">
			<div class="row d-flex justify-content-center align-items-center h-100">
			<div class="col-md-9 col-lg-6 col-xl-5">
				<img src="<?= $logo->image_path ?>"
				class="img-fluid" alt="Sample image">
			</div>
			<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
				<h1 style="text-align: center;">Reservation</h1>
				<form method="post" action="<?= base_url() ?>index.php/reservasi/send_reservation">
				<!-- Email input -->
				<div data-mdb-input-init class="form-outline mb-4">
					<input type="text" name="name" class="form-control form-control-lg"
					placeholder="Enter Name" />
				</div>
				<div data-mdb-input-init class="form-outline mb-4">
					<input type="text" name="nohp" class="form-control form-control-lg"
					placeholder="Enter Phone Number" />
				</div>

				<!-- Password input -->
				<div data-mdb-input-init class="form-outline mb-3">
					<input type="number" name="pax" class="form-control form-control-lg"
					placeholder="Enter Pax" />
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<label for="">Date</label>
						<input type="date" name="date" class="form-control form-control-lg" placeholder="Enter Pax (Left)" />
					</div>
					<div class="col-md-6">
						<label for="">Hour</label>
						<input type="time" name="hour" class="form-control form-control-lg" placeholder="Enter Pax (Right)" />
					</div>
				</div>


				
				<button type="submit" class="btn btn-primary mt-2"
					style="background-color:<?= $cn->darkcolor ?>; width: 100%;">
					Send Reservation
				</button>


				</form>
			</div>
			</div>
		</div>
		</section>
</body>
    
<?php $this->load->view('template/footer') ?>

