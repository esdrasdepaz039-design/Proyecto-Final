<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nuevo Préstamo<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Registrar Nuevo Préstamo</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/rentals" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Préstamos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="/rentals/create" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="client_id" class="form-label">Cliente</label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="">Seleccione un cliente</option>
                    <?php if (isset($clients) && !empty($clients)): ?>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>"><?= $client['name'] ?> (<?= $client['email'] ?>)</option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay clientes disponibles</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="book_id" class="form-label">Libro</label>
                <select class="form-select" id="book_id" name="book_id" required>
                    <option value="">Seleccione un libro</option>
                    <?php if (isset($books) && !empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <option value="<?= $book['id'] ?>"><?= $book['title'] ?> (<?= $book['author'] ?>) - Disponibles: <?= $book['available'] ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay libros disponibles</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="rental_date" class="form-label">Fecha de Préstamo</label>
                <input type="date" class="form-control" id="rental_date" name="rental_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="return_date" class="form-label">Fecha de Devolución</label>
                <input type="date" class="form-control" id="return_date" name="return_date" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>