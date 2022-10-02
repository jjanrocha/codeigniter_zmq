<?php

class Status extends CI_Model
{

    public function listar_status()
    {
        $this->load->database();
        session_start();
        $id_agente = $_SESSION['usuario_id'];

        $this->db->select('*');
        $this->db->from('status_usuario');
        $this->db->order_by('status', 'ASC');
        $lista_status['todos_status'] = $this->db->get()->result_array();

        $this->db->select('status_usuario.id, status_usuario.status');
        $this->db->from('usuarios');
        $this->db->join('status_usuario', "usuarios.status_id = status_usuario.id AND usuarios.id = $id_agente");
        $lista_status['status_agente'] = $this->db->get()->result_array();

        return $lista_status;
    }

    public function alterar_status($id_agente, $novo_status_id)
    {
        $this->load->database();
    
        $this->db->set('status_id', $novo_status_id);
        $this->db->where('id', $id_agente);
        $this->db->update('usuarios');
        return $this->db->affected_rows();
    }
}
