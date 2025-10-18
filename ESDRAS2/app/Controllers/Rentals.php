<?php

namespace App\Controllers;

use App\Models\RentalModel;
use App\Models\BookModel;
use App\Models\ClientModel;

class Rentals extends BaseController
{
    protected $rentalModel;
    protected $bookModel;
    protected $clientModel;
    
    public function __construct()
    {
        $this->rentalModel = new RentalModel();
        $this->bookModel = new BookModel();
        $this->clientModel = new ClientModel();
    }
    
    public function index()
    {
        try {
            $data['rentals'] = $this->rentalModel->getWithDetails();
        } catch (\Exception $e) {
            $data['rentals'] = [];
            session()->setFlashdata('error', 'La tabla de préstamos no existe. Por favor, importa la base de datos.');
        }
        return view('rentals/index', $data);
    }
    
    public function new()
    {
        try {
            $data['books'] = $this->bookModel->where('available >', 0)->findAll();
            $data['clients'] = $this->clientModel->findAll();
        } catch (\Exception $e) {
            $data['books'] = [];
            $data['clients'] = [];
            session()->setFlashdata('error', 'Error al cargar libros o clientes. Por favor, importa la base de datos.');
        }
        return view('rentals/new', $data);
    }
    
    public function create()
    {
        $data = [
            'client_id' => $this->request->getPost('client_id'),
            'book_id' => $this->request->getPost('book_id'),
            'rental_date' => $this->request->getPost('rental_date'),
            'return_date' => $this->request->getPost('return_date'),
            'status' => 'active'
        ];
        
        try {
            // Iniciar transacción
            $db = \Config\Database::connect();
            $db->transBegin();
            
            // Insertar el préstamo
            if ($this->rentalModel->insert($data)) {
                // Actualizar disponibilidad del libro
                $book = $this->bookModel->find($data['book_id']);
                if ($book && $book['available'] > 0) {
                    $this->bookModel->update($data['book_id'], ['available' => $book['available'] - 1]);
                    $db->transCommit();
                    session()->setFlashdata('message', 'Préstamo registrado correctamente');
                } else {
                    $db->transRollback();
                    session()->setFlashdata('error', 'El libro no está disponible para préstamo');
                }
            } else {
                $db->transRollback();
                session()->setFlashdata('error', 'Error al registrar el préstamo: ' . implode('<br>', $this->rentalModel->errors()));
            }
        } catch (\Exception $e) {
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }
            session()->setFlashdata('error', 'No se pudo guardar el préstamo. Por favor, importa la base de datos primero.');
        }
        
        return redirect()->to('/rentals');
    }
}