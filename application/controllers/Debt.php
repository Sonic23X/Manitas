<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Debt_model");
    $this->load->model('User_model');
  }

  function Index()
  {
    if($this->session->userdata('login')){

      $info = $this->User_model->GetUserId($this->session->userdata('id'));

      $data = array('title' => 'Deudas');
  		$this->load->view('body/head', $data);

      $data = array('usuario' => $info->Nick);
			$this->load->view('body/nav', $data);

      if($info->Tipo == "Administrador")
      {
        $activos = array('inicio' => false, 'inventario' => false,
  			'reporte' => false, 'deudas' => true, 'usuarios' => false,
        'ver' => true);
      }
      else
      {
        $activos = array('inicio' => false, 'inventario' => false,
        'reporte' => false, 'deudas' => true, 'usuarios' => false,
        'ver' => false);
      }

			//barra lateral
			$this->load->view('body/sidebar', $activos);

      //cuerpo
      $data['deudas'] = $this->Debt_model->GetNotas();
      $this->load->view('options/debt', $data);


      //modales
			$this->load->view('options/buy');
			$this->load->view('options/about');

  		$this->load->view('body/footer');
    }
    else
    {
      header("Location: ".base_url('Login'));
    }
  }

  function Create()
  {
    $post = $this->input->post();

    $data['Nombre'] = $post['nombre'];
    $data['Monto'] = $post['monto'];

    $this->Debt_model->InsertNota($data);
  }

  function GetMonto()
  {
    $folio = $this->input->post('folio');

    $info = $this->Debt_model->GetDeuda($folio);

    if($info != null)
      echo $info->Monto;
  }

  function Update()
  {
    $post = $this->input->post();

    $data['ID'] = $post['id'];
    $data['Monto'] = $post['monto'];

    $this->Debt_model->Update($data);
  }

  function DeleteThis()
  {
    $datos = $this->input->post();

    $folio = $datos['folio'];

    $bool = $this->Debt_model->Delete($folio);

    if($bool)
      echo "¡Nota borrado con exito!";
  }

}
