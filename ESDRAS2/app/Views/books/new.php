<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nuevo Libro<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Agregar Nuevo Libro</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/books" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Libros
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="/books/create" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="author" class="form-label">Autor</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
            </div>
            
            <div class="mb-3">
                <label for="publication_year" class="form-label">Año de Publicación</label>
                <input type="number" class="form-control" id="publication_year" name="publication_year" required>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            
            <div class="mb-3">
                <label for="quantity" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required min="1" value="1">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Libro</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>