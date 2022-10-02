<?php
defined('BASEPATH') or exit('No direct script access allowed');

class listar_status extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('status');
    }

    public function index()
    {
        $retorno = $this->status->listar_status();

        echo json_encode($retorno);
    }
}
