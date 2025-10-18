<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Libros</h5>
                <p class="display-4">0</p>
                <a href="/books" class="btn btn-outline-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>Clientes</h5>
                <p class="display-4">0</p>
                <a href="/clients" class="btn btn-outline-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h5>Préstamos</h5>
                <p class="display-4">0</p>
                <a href="/rentals" class="btn btn-outline-dark btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5>Multas</h5>
                <p class="display-4">0</p>
                <a href="/fines" class="btn btn-outline-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Últimos préstamos</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">No hay préstamos recientes.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Libros populares</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">No hay datos disponibles.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>