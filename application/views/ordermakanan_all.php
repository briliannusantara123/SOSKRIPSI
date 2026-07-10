<?php $this->load->view('template/headmenu') ?>

<style>
.menu-grid-img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
}
.menu-grid-card {
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}
</style>

<div class="container mt-3" id="menuContainer">

<?php foreach ($grouped_items as $sub => $items): ?>
<section id="<?= str_replace(' ','_',$sub) ?>" class="mb-4">

<h5 class="fw-bold"><?= $sub ?></h5>

<div class="row g-3">
<?php foreach ($items as $i): ?>
<div class="col-6 menu-item">
<div class="card menu-grid-card p-2">

<img
    class="menu-grid-img lazy-img"
    src="<?= $logo->image_path ?>"
    data-src="<?= $i->image_path ?: $logo->image_path ?>"
    loading="lazy"
>

<strong><?= $i->description ?></strong>
<div class="text-muted">Rp <?= number_format($i->harga_weekend) ?></div>

<button class="btn btn-outline-custom btn-sm w-100 mt-2">Add</button>

</div>
</div>
<?php endforeach ?>
</div>

</section>
<?php endforeach ?>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const imgs = document.querySelectorAll('.lazy-img');

    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.src = e.target.dataset.src;
                io.unobserve(e.target);
            }
        });
    }, { rootMargin: '150px' });

    imgs.forEach(img => io.observe(img));
});
</script>
<script>
let offset = 0;
let loading = false;

window.addEventListener('scroll', () => {
    if (loading) return;

    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        loading = true;

        fetch(`<?= base_url('menu/load-more') ?>?offset=${offset}`)
        .then(r => r.text())
        .then(html => {
            if (html.trim()) {
                document.getElementById('menuContainer')
                    .insertAdjacentHTML('beforeend', html);
                offset += 20;
            }
            loading = false;
        });
    }
});
</script>
