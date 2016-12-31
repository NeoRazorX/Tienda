<?php

require_model('articulo.php');
require_model('divisa.php');

class ventas_tienda extends fs_controller
{
	public $articulo;
	public $paramtienda;
    public $buscar;
    public $articulos;
    public $offset;
    public $mostrar;

   public function __construct()
   {
      parent::__construct(__CLASS__, 'Portada', 'ventas');
   }

   protected function private_core()
   {
   	$fsvar = new fs_var();
    $this->paramtienda = array(
        'mailtienda' => '',
        'divtienda' => '',
    );

     $this->paramtienda = $fsvar->array_get($this->paramtienda, FALSE);

    if (isset($_POST['mailtienda']))
    {
	    $fsvar1 = new fs_var();
	    $this->dato = $_POST['mailtienda'];
	    $fsvar1->simple_save('mailtienda', $this->dato);
	    $this->dato2 = $_POST['divtienda'];
	    $fsvar1->simple_save('divtienda', $this->dato2);

	    $this->new_message("Parámetros configurados correctamente.");
    }
   }

   protected function public_core()
   {
   	$this->template = 'tienda_public/store';
   	$this->page_title = $this->empresa->nombrecorto;
    $this->page_description = 'Tienda de' .$this->empresa->nombrecorto;
    $articulo = new articulo();
    $this->articulo = new articulo();
		$this->offset = 0;
    if( isset($_GET['offset']) )
    {
        $this->offset = intval($_GET['offset']);
    }
    $this->mostrar = 'portada';
    if( isset($_GET['mostrar']) )
    {
        $this->mostrar = $_GET['mostrar'];
    }

    $this->buscar = '';
    if( isset($_REQUEST['buscar']) )
    {
        $this->buscar = $_REQUEST['buscar'];
    }
    if($this->buscar != '')
    {
        $this->articulos = $articulo->search($this->buscar, $this->offset);
    }
    else if($this->mostrar == 'portada')
    {
        if($this->offset > 0)
        {
            $this->articulos = $articulo->all($this->offset, 'referencia DESC');
        }
    }
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

   public function anterior_url()
   {
      return $this->url().'&mostrar='.$this->mostrar.'&buscar='.$this->buscar.'&offset='.($this->offset-FS_ITEM_LIMIT);
   }

   public function siguiente_url()
   {
      return $this->url().'&mostrar='.$this->mostrar.'&buscar='.$this->buscar.'&offset='.($this->offset+FS_ITEM_LIMIT);
   }

}
