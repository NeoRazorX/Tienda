<?php

require_model('articulo.php');
require_model('divisa.php');
require_model('empresa.php');
require_model('pais.php');
require_model('cliente.php');

class ventas_tienda_ajax_car extends fs_controller
{
	public $articulo;

   public function __construct()
   {
      parent::__construct(__CLASS__, 'Pagar productos', 'Tienda', FALSE, FALSE);
   }

   protected function private_core()
   {

   }

   protected function public_core()
   {
     $this->template = 'tienda_public/ajax_car';

     $articulo = new articulo();

     $json = $articulo->get($_GET['ref']);

     echo json_encode($json);
   }

   public function full_url()
   {
      $url = $this->empresa->web;

      if( isset($_SERVER['SERVER_NAME']) )
      {
         if($_SERVER['SERVER_NAME'] == 'localhost')
         {
            $url = 'http://'.$_SERVER['SERVER_NAME'];

            if( isset($_SERVER['REQUEST_URI']) )
            {
               $aux = parse_url( str_replace('/index.php', '', $_SERVER['REQUEST_URI']) );
               $url .= $aux['path'];
            }
         }
      }

      return $url;
   }
  }
