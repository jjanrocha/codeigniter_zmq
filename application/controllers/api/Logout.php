<?php
defined('BASEPATH') or exit('No direct script access allowed');

class logout extends CI_Controller
{
    public function index()
    {
        session_start();
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_nome']);
        session_destroy();

        $retorno = ['msg' => 'Logout realizado com sucesso'];
        echo json_encode($retorno);
    }
}
