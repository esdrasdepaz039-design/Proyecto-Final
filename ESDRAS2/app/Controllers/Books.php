<?php

namespace App\Controllers;

use App\Models\BookModel;

class Books extends BaseController
{
    protected $bookModel;
    
    public function __construct()
    {
        $this->bookModel = new BookModel();
    }
    
    public function index()
    {
        try {
            $data['books'] = $this->bookModel->findAll();
        } catch (\Exception $e) {
            // Si hay un error (por ejemplo, la tabla no existe), mostramos una lista vacía
            $data['books'] = [];
            session()->setFlashdata('error', 'La tabla de libros no existe. Por favor, importa la base de datos.');
        }
        return view('books/index', $data);
    }
    
    public function new()
    {
        return view('books/new');
    }
    
    public function create()
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'isbn' => $this->request->getPost('isbn'),
            'publication_year' => $this->request->getPost('publication_year'),
            'category' => $this->request->getPost('category'),
            'quantity' => $this->request->getPost('quantity'),
            'available' => $this->request->getPost('quantity') // Inicialmente todos disponibles
        ];
        
        try {
            if ($this->bookModel->insert($data)) {
                session()->setFlashdata('message', 'Libro agregado correctamente');
            } else {
                session()->setFlashdata('error', 'Error al agregar el libro: ' . implode('<br>', $this->bookModel->errors()));
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'No se pudo guardar el libro. Por favor, importa la base de datos primero. El archivo SQL se encuentra en la raíz del proyecto (esdras_library.sql).');
        }
        
        return redirect()->to('/books');
    }
}