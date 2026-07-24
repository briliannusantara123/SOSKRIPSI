<?php $this->load->view('template/headmenu') ?>
<?php $previous = "javascript:history.go(-1)";
if (isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
} ?>

<div class="container-fluid" style="background: linear-gradient(90deg, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>); color: white;">
  <div class="container py-4">
    <div class="d-flex align-items-center">
      <a href="<?= $linkback ?>" class="text-white me-3" style="text-decoration:none;">
        <i class="bi bi-arrow-left" style="font-size: 28px; text-shadow: 1px 1px 2px rgba(0,0,0,.4); color: black;"></i>
      </a>
      <h2 class="m-0" style="color: black;"><strong>Feedback & Suggestions</strong></h2>
    </div>
  </div>
</div>

<div class="container my-4">
  <form action="<?= base_url() ?>index.php/review/save/<?= $nomeja;?>/<?= $sub ?>" method="post">
    <input type="hidden" name="linkback" value="<?= $linkback ?>">

    <div class="row">
      <div class="col-12">
        <p class="text-muted">We appreciate your feedback. Please tell us what we can improve and what you liked.</p>
      </div>
    </div>

    <!-- 1. Food & Beverage Taste -->
    <div class="card mb-3 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">1. Food &amp; Beverage Taste</h5>
      </div>
      <div class="card-body" id="rasa-list">
        <p class="small text-muted">Select the type, enter the item name, then provide praise or criticism. Add more items if needed.</p>
        <div class="rasa-item row g-2 align-items-end mb-2">
          <div class="col-md-2">
            <label class="form-label small">Type</label>
            <select name="rasa_type[]" class="form-select">
              <option value="Food">Food</option>
              <option value="Beverage">Beverage</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Item Name</label>
						<select name="rasa_item[]" class="form-select">
							<option value="">Select an item</option>
							<?php foreach($order_bill_line as $item): ?>
								<?php
									$item_category = strtoupper(trim($item->category ?? ''));
									$item_group = ($item_category === 'MINUMAN') ? 'beverage' : 'food';
								?>
								<option value="<?= htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8') ?>" data-category="<?= $item_group ?>">
									<?= htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8') ?>
								</option>
							<?php endforeach; ?>
						</select>
          </div>
          <div class="col-md-3">
            <label class="form-label small">Praise</label>
            <input type="text" name="rasa_pujian[]" class="form-control" placeholder="What did you like?">
          </div>
          <div class="col-md-2">
            <label class="form-label small">Criticism</label>
            <input type="text" name="rasa_kritik[]" class="form-control" placeholder="Enter brief criticism">
          </div>
          <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-remove-item" style="display:none;">×</button>
          </div>
        </div>
        <div>
          <button type="button" id="add-rasa" class="btn btn-link btn-sm">+ Add another item</button>
        </div>
      </div>
    </div>

    <!-- 2. Facilities -->
    <div class="card mb-3 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">2. Facilities</h5>
      </div>
      <div class="card-body" id="fasilitas-list">
        <p class="small text-muted">Select a facility then provide praise or criticism. Add more facilities if needed.</p>
        <div class="fasilitas-item row g-2 align-items-end mb-2">
          <div class="col-md-4">
            <label class="form-label small">Fasilitas</label>
            <select name="fasilitas_name[]" class="form-select">
							<?php foreach($category as $c): ?>
								<?php if (strtolower($c->category) === 'fasilitas'): ?>
									<option value="<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Praise</label>
            <input type="text" name="fasilitas_pujian[]" class="form-control" placeholder="Praise for the facility">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Criticism</label>
            <input type="text" name="fasilitas_kritik[]" class="form-control" placeholder="Criticism for the facility">
          </div>
          <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-remove-item" style="display:none;">×</button>
          </div>
        </div>
        <div>
          <button type="button" id="add-fasilitas" class="btn btn-link btn-sm">+ Add another facility</button>
        </div>
      </div>
    </div>

    <!-- 3. Pricing -->
    <div class="card mb-3 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">3. Pricing</h5>
      </div>
      <div class="card-body" id="harga-list">
        <p class="small text-muted">Select a pricing category then provide praise or criticism.</p>
        <div class="harga-item row g-2 align-items-end mb-2">
          <div class="col-md-4">
            <label class="form-label small">Category</label>
            <select name="harga_cat[]" class="form-select">
              <?php foreach($category as $c): ?>
								<?php if (strtolower($c->category) === 'harga'): ?>
									<option value="<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Praise</label>
            <input type="text" name="harga_pujian[]" class="form-control" placeholder="Praise regarding pricing">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Criticism</label>
            <input type="text" name="harga_kritik[]" class="form-control" placeholder="Criticism regarding pricing">
          </div>
          <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-remove-item" style="display:none;">×</button>
          </div>
        </div>
        <div>
          <button type="button" id="add-harga" class="btn btn-link btn-sm">+ Add another pricing category</button>
        </div>
      </div>
    </div>

    <!-- 4. Service -->
    <div class="card mb-3 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">4. Service</h5>
      </div>
      <div class="card-body" id="pelayanan-list">
        <p class="small text-muted">Select a service sub-category then provide praise or criticism. Add more categories if needed.</p>
        <div class="mb-2">
					<?php foreach($category as $c): ?>
						<?php if (strtolower($c->category) === 'pelayanan'): ?>
							<button type="button" class="btn btn-outline-primary btn-sm me-1 quick-add" data-val="<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>">
								<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>
							</button>
						<?php endif; ?>
					<?php endforeach; ?>
        </div>
        <div class="pelayanan-items">
          <!-- dynamic items will be appended here -->
        </div>
        <div class="mt-2">
          <button type="button" id="add-pelayanan" class="btn btn-link btn-sm">+ Add another service category</button>
        </div>
      </div>
    </div>

    <!-- 5. Cleanliness -->
    <div class="card mb-3 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">5. Cleanliness</h5>
      </div>
      <div class="card-body" id="kebersihan-list">
        <p class="small text-muted">Select an area then provide praise or criticism.</p>
        <div class="kebersihan-item row g-2 align-items-end mb-2">
          <div class="col-md-4">
            <label class="form-label small">Area</label>
            <select name="kebersihan_area[]" class="form-select">
							<?php foreach($category as $c): ?>
								<?php if (strtolower($c->category) === 'kebersihan'): ?>
									<option value="<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>">
										<?= htmlspecialchars($c->description, ENT_QUOTES, 'UTF-8') ?>
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small">Praise</label>
            <input type="text" name="kebersihan_pujian[]" class="form-control" placeholder="Praise regarding cleanliness">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Criticism</label>
            <input type="text" name="kebersihan_kritik[]" class="form-control" placeholder="Criticism regarding cleanliness">
          </div>
          <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-remove-item" style="display:none;">×</button>
          </div>
        </div>
        <div>
          <button type="button" id="add-kebersihan" class="btn btn-link btn-sm">+ Add another cleanliness category</button>
        </div>
      </div>
    </div>

    <div class="d-grid gap-2 mb-5">
      <button type="submit" class="btn btn-primary btn-lg" style="background-color: <?= $cn->color ?>; border-color: <?= $cn->color ?>;">
        <strong>Send</strong>
      </button>
    </div>

  </form>
</div>

<?php $this->load->view('template/footer') ?>

<script>
// Simple dynamic add/remove handlers for review form
(function(){
  function makeRemove(button){
    button.addEventListener('click', function(){
      var row = button.closest('.row');
      if(row) row.remove();
    });
  }

  function filterRasaItems(itemSelect){
    var row = itemSelect.closest('.rasa-item');
    if(!row) return;

    var typeSelect = row.querySelector('select[name="rasa_type[]"]');
    var selectedType = (typeSelect && typeSelect.value) ? typeSelect.value.toLowerCase() : 'food';
    var options = itemSelect.querySelectorAll('option');
    var selectedValue = itemSelect.value;

    options.forEach(function(opt){
      if(!opt.value){
        opt.style.display = '';
        return;
      }

      var group = opt.getAttribute('data-category') || 'food';
      var show = (selectedType === 'beverage' && group === 'beverage') || (selectedType === 'food' && group === 'food');
      opt.style.display = show ? '' : 'none';
    });

    var availableValues = Array.prototype.slice.call(options).filter(function(opt){
      return opt.value && opt.style.display !== 'none';
    });

    if(selectedValue && !availableValues.some(function(opt){ return opt.value === selectedValue; })){ 
      itemSelect.value = '';
    }
  }

  function initRasaRow(row){
    var typeSelect = row.querySelector('select[name="rasa_type[]"]');
    var itemSelect = row.querySelector('select[name="rasa_item[]"]');

    if(typeSelect && itemSelect){
      typeSelect.addEventListener('change', function(){ filterRasaItems(itemSelect); });
      filterRasaItems(itemSelect);
    }
  }

  document.querySelectorAll('.rasa-item').forEach(initRasaRow);

  // Add rasa
  document.getElementById('add-rasa').addEventListener('click', function(){
    var container = document.getElementById('rasa-list');
    var template = container.querySelector('.rasa-item');
    var clone = template.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function(i){ i.value = ''; });
    clone.querySelector('select[name="rasa_type[]"]').value = 'Food';
    clone.querySelector('.btn-remove-item').style.display = 'inline-block';
    makeRemove(clone.querySelector('.btn-remove-item'));
    container.insertBefore(clone, template.nextSibling);
    initRasaRow(clone);
  });

  // Add fasilitas
  document.getElementById('add-fasilitas').addEventListener('click', function(){
    var container = document.getElementById('fasilitas-list');
    var template = container.querySelector('.fasilitas-item');
    var clone = template.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function(i){ i.value = ''; });
    clone.querySelector('.btn-remove-item').style.display = 'inline-block';
    makeRemove(clone.querySelector('.btn-remove-item'));
    container.insertBefore(clone, template.nextSibling);
  });

  // Add harga
  document.getElementById('add-harga').addEventListener('click', function(){
    var container = document.getElementById('harga-list');
    var template = container.querySelector('.harga-item');
    var clone = template.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function(i){ i.value = ''; });
    clone.querySelector('.btn-remove-item').style.display = 'inline-block';
    makeRemove(clone.querySelector('.btn-remove-item'));
    container.insertBefore(clone, template.nextSibling);
  });

  // Add kebersihan
  document.getElementById('add-kebersihan').addEventListener('click', function(){
    var container = document.getElementById('kebersihan-list');
    var template = container.querySelector('.kebersihan-item');
    var clone = template.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(function(i){ i.value = ''; });
    clone.querySelector('.btn-remove-item').style.display = 'inline-block';
    makeRemove(clone.querySelector('.btn-remove-item'));
    container.insertBefore(clone, template.nextSibling);
  });

  // Pelayanan: add item row (used by quick-add and add custom)
  function addPelayanan(name){
    var container = document.querySelector('.pelayanan-items');
    var div = document.createElement('div');
    div.className = 'row g-2 align-items-end mb-2';
    div.innerHTML = '\n      <div class="col-md-4">\n        <label class="form-label small">Category</label>\n        <input type="text" name="pelayanan_name[]" class="form-control" value="'+(name||'')+'">\n      </div>\n      <div class="col-md-4">\n        <label class="form-label small">Praise</label>\n        <input type="text" name="pelayanan_pujian[]" class="form-control">\n      </div>\n      <div class="col-md-3">\n        <label class="form-label small">Criticism</label>\n        <input type="text" name="pelayanan_kritik[]" class="form-control">\n      </div>\n      <div class="col-md-1 text-end">\n        <button type="button" class="btn btn-outline-secondary btn-sm btn-remove-item">×</button>\n      </div>';
    container.appendChild(div);
    makeRemove(div.querySelector('.btn-remove-item'));
  }

  document.querySelectorAll('.quick-add').forEach(function(btn){
    btn.addEventListener('click', function(){ addPelayanan(btn.getAttribute('data-val')); });
  });

  document.getElementById('add-pelayanan').addEventListener('click', function(){ addPelayanan(''); });

  // Attach remove to any existing (hidden) remove buttons just in case
  document.querySelectorAll('.btn-remove-item').forEach(function(b){ if(b.style.display !== 'none') makeRemove(b); });
})();
</script>
