<?php

namespace App\Models;

use CodeIgniter\Model;

class RentalModel extends Model
{
    protected $table      = 'rentals';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    
    protected $allowedFields = [
        'client_id', 'book_id', 'rental_date', 'return_date', 'status'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Validation
    protected $validationRules = [
        'client_id'    => 'required|numeric',
        'book_id'      => 'required|numeric',
        'rental_date'  => 'required|valid_date',
        'return_date'  => 'required|valid_date'
    ];
    
    // Obtener préstamos con información de libros y clientes
    public function getWithDetails()
    {
        return $this->select('rentals.*, books.title as book_title, clients.name as client_name')
                    ->join('books', 'books.id = rentals.book_id')
                    ->join('clients', 'clients.id = rentals.client_id')
                    ->findAll();
    }
}