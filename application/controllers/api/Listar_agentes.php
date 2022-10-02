<?php
defined('BASEPATH') or exit('No direct script access allowed');

class listar_agentes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('agentes');
    }

    public function index()
    {
        $retorno = $this->agentes->listar_agentes();

        echo json_encode($retorno);
    }
}