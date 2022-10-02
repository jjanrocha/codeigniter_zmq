<?php
defined('BASEPATH') or exit('No direct script access allowed');

require '/var/www/html/codeigniter_zmq/vendor/autoload.php';

class cadastrar_agente extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('agentes');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->database();
        session_start();
    }

    public function index()
    {

        $retorno = array();

        $this->form_validation->set_rules(
            'nome-agente',
            'Nome',
            'required|min_length[3]|max_length[20]',
        );

        $this->form_validation->set_rules(
            'usuario-agente',
            'Usuário',
            'required|is_unique[usuarios.usuario]|min_length[3]|max_length[20]',
            array(
                'is_unique' => 'Usuário já cadastrado.',
            )
        );

        $this->form_validation->set_rules(
            'tipo-usuario-id',
            'Tipo de Usuário',
            'required',
        );

        $this->form_validation->set_message('required', 'O campo {field} é de preenchimento obrigatório.');
        $this->form_validation->set_message('min_length', 'O campo {field} deve possuir no mínimo {param} caracteres.');
        $this->form_validation->set_message('max_length', 'O campo {field} deve possuir no máximo {param} caracteres.');

        if ($this->form_validation->run() == false) {
            $retorno['errors'] = $this->form_validation->error_array();
        } else {
            $mensagem = $this->agentes->cadastrar_agente();
            $cod = explode('|', $mensagem);

            if ($cod[0] == '1') {
                $retorno['success'] = 'Cadastro realizado com sucesso.';

                $entryData = array(
                    'category' => 'PainelAgente',
                    'subcategory' => 'Insert',
                    'nomeSupervisor' => $_SESSION['usuario_nome'],
                    'nomeAgenteCadastrado' => $this->input->post('nome-agente')
                );

                $context = new ZMQContext();
                $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->connect("tcp://localhost:5555");
                $socket->send(json_encode($entryData));
            } else {
                $retorno['errors'] = ['msgModalNovoAgente' => 'Houve um erro na inserção do agente.'];
            }
        }

        echo json_encode($retorno);
    }
}
