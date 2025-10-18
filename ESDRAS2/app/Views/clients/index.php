<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Clientes<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Clientes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/clients/new" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('message')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('message') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay clientes registrados</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= $client['name'] ?></td>
                        <td><?= $client['email'] ?></td>
                        <td><?= $client['phone'] ?></td>
                        <td>
                            <a href="/clients/edit/<?= $client['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>