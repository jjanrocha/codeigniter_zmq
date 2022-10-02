<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function index()
	{
		session_start();
		$this->load->helper('url');
		$this->load->view('layout/header');

		if (isset($_SESSION['usuario_id'])) {
			if ($_SESSION['tipo_usuario_id'] == '1') {
				$this->load->view('alterar_status');
			} elseif ($_SESSION['tipo_usuario_id'] == '2') {
				$this->load->view('monitoramento_agentes');
			}
		} else {
			$this->load->view('login');
		}
		$this->load->view('layout/footer');
	}
}
