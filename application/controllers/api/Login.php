<?php
defined('BASEPATH') or exit('No direct script access allowed');

class login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
        $this->load->model('usuario');
    }

    public function index()
    {
        $this->form_validation->set_rules(
            'usuario',
            'Usuário',
            'required',
            array(
                'required' => 'O campo {field} é de preenchimento obrigatório.'
            )
        );

        if ($this->form_validation->run() == false) {
            $retorno['errors'] = $this->form_validation->error_array();
        } else {
            $usuario_valido = $this->usuario->login();

            if ($usuario_valido) {
                session_start();
                $_SESSION['usuario_id'] = $usuario_valido->id;
                $_SESSION['usuario_nome'] = $usuario_valido->nome;
                $_SESSION['tipo_usuario_id'] = $usuario_valido->tipo_usuario_id;
                $retorno['success'] = 'Usuário logado com sucesso.';
            } else {
                $retorno['errors'] = ['usuario' => 'Usuário inválido.'];
            }
        }

        echo json_encode($retorno);
    }
}
