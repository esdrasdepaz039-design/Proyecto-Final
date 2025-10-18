<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Préstamos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Préstamos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/rentals/new" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
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
                        <th>Cliente</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rentals)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay préstamos registrados</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td><?= $rental['id'] ?></td>
                        <td><?= $rental['client_name'] ?? $rental['client_id'] ?></td>
                        <td><?= $rental['book_title'] ?? $rental['book_id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($rental['rental_date'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($rental['return_date'])) ?></td>
                        <td>
                            <?php if ($rental['status'] == 'active'): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php elseif ($rental['status'] == 'returned'): ?>
                                <span class="badge bg-info">Devuelto</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/rentals/return/<?= $rental['id'] ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-undo"></i>
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