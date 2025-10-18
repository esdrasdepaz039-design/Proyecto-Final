<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Clients extends BaseController
{
    protected $clientModel;
    
    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }
    
    public function index()
    {
        try {
            $data['clients'] = $this->clientModel->findAll();
        } catch (\Exception $e) {
            $data['clients'] = [];
            session()->setFlashdata('error', 'La tabla de clientes no existe. Por favor, importa la base de datos.');
        }
        return view('clients/index', $data);
    }
    
    public function new()
    {
        return view('clients/new');
    }
    
    public function create()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address')
        ];
        
        try {
            if ($this->clientModel->insert($data)) {
                session()->setFlashdata('message', 'Cliente agregado correctamente');
            } else {
                session()->setFlashdata('error', 'Error al agregar el cliente: ' . implode('<br>', $this->clientModel->errors()));
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'No se pudo guardar el cliente. Por favor, importa la base de datos primero.');
        }
        
        return redirect()->to('/clients');
    }
}