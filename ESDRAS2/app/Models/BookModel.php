<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table      = 'books';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    
    protected $allowedFields = [
        'title', 'author', 'isbn', 'publication_year', 
        'category', 'quantity', 'available'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Validation
    protected $validationRules = [
        'title'     => 'required|min_length[3]',
        'author'    => 'required|min_length[3]',
        'isbn'      => 'required',
        'publication_year' => 'required|numeric',
        'category'  => 'required',
        'quantity'  => 'required|numeric|greater_than[0]',
    ];
}