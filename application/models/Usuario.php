<?php

class Usuario extends CI_Model
{

    public function login()
    {
        $this->load->database();
        
        $usuario_post = $this->input->post('usuario');

        return $this->db->get_where('usuarios',['usuario' => $usuario_post])->row();

    }
}
