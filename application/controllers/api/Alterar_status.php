<?php
defined('BASEPATH') or exit('No direct script access allowed');

require '/var/www/html/codeigniter_zmq/vendor/autoload.php';

class alterar_status extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('status');
        session_start();
    }

    public function index()
    {
        $id_agente = $_SESSION['usuario_id'];
        $novo_status_id = $this->input->post('status_id');
        $retorno = $this->status->alterar_status($id_agente, $novo_status_id);

        if ($retorno == '1') {
            $resposta['mensagem'] = 'O status foi alterado com sucesso.';
        } elseif ($retorno == '0') {
            $resposta['mensagem'] = 'O status selecionado é o mesmo que o anterior.';
        }

        $entryData = array(
            'category' => 'PainelAgente',
            'subcategory' => 'Update',
            'nomeAgente' => $_SESSION['usuario_nome'],
            'novoStatusID' => $this->input->post('status_id')
        );

        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");
        $socket->send(json_encode($entryData));

        echo json_encode($resposta);
    }
}
