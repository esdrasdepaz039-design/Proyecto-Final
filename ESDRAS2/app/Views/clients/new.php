<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nuevo Cliente<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Agregar Nuevo Cliente</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/clients" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Clientes
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="/clients/create" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Dirección</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>