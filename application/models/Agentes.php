<?php

class Agentes extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function listar_agentes()
    {
        $this->db->select('usuarios.id, usuarios.nome, status_usuario.status, tipos_usuario.tipo_usuario');
        $this->db->from('usuarios');
        $this->db->join('tipos_usuario', 'tipos_usuario.id = usuarios.tipo_usuario_id', 'inner');
        $this->db->join('status_usuario', 'status_usuario.id = usuarios.status_id', 'inner');
        $this->db->where('usuarios.tipo_usuario_id', '1');
        $this->db->order_by('usuarios.nome', 'ASC');
        $lista_agentes = $this->db->get()->result_array();

        return $lista_agentes;
    }

    public function cadastrar_agente()
    {
        $data = array(
            'nome' =>  $this->input->post('nome-agente'),
            'usuario' => $this->input->post('usuario-agente'),
            'tipo_usuario_id' => $this->input->post('tipo-usuario-id'),
            'status_id' => 1
        );

        if ($this->db->insert('usuarios', $data)) {
            $mensagem = '1|Usuário cadastrado com sucesso.';
        } else {
            $mensagem = '0|Houve erro no cadastro do usuário.';
        }

        return $mensagem;
    }
}
