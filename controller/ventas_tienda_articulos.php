<?php

require_model('articulo.php');
require_model('divisa.php');
require_model('empresa.php');
require_model('pais.php');
require_model('cliente.php');

class ventas_tienda_articulos extends fs_controller
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

    $fsvar = new fs_var();
    $this->paramtienda = array(
        'mailtienda' => '',
        'divtienda' => '',
    );
    $this->paramtienda = $fsvar->array_get($this->paramtienda, FALSE);
    $this->template = 'tienda_public/product';
    $this->page_title = $this->empresa->nombrecorto;
    $this->page_description = 'Tienda de' .$this->empresa->nombrecorto;
    $divisa = new divisa();
    $this->divisa = new divisa();
    $empresa = new empresa();
    $this->pais = new pais();
    $this->empresa = new empresa();
    $articulo = new articulo();
    $this->articulo = new articulo();
    $this->cliente = new cliente();


      if( isset($_POST['referencia']) )
      {
         $this->articulo = $articulo->get($_POST['referencia']);
      }
      else if( isset($_GET['ref']) )
      {
         $this->articulo = $articulo->get($_GET['ref']);
      }

      if( isset($_POST['cmd']) )
      {
         $paypal_account = "paypal@micodigophp.com"; //Mi cuenta de paypal
         $paypal_currency = "USD"; //La moneda con la que estamos trabajando

         /*
         En la siguiente linea formaremos el query para mandar al servidor de paypal y verificar el pago.
         */
         $req = 'cmd=_notify-validate';
         foreach ($_POST as $key => $value)
         {
              $value = urlencode(stripslashes($value));
              $req .= "&$key=$value";
         }

         $test = 'si'; //Si estamos usando el sandbox, lo cambiamos a "si", de lo contrario lo mantendras en "no"

         if($test == 'si')
         {
             $url="https://www.sandbox.paypal.com/cgi-bin/webscr";
         }
         else
         {
             $url="https://www.paypal.com/cgi-bin/webscr";
         }

         $item_name = $_POST['item_name']; //El nombre de nuestro artículo o producto.
         $order_id = $_POST['item_number']; //El numero o ID de nuestro producto o invoice.
         $payment_status = $_POST['payment_status']; //El estado del pago
         $amount = $_POST['mc_gross']; //El monto total pagado
         $payment_currency = $_POST['mc_currency']; //La moneda con que se ha hecho el pago
         $transaction_id = $_POST['txn_id']; //EL ID o Código de transacción.
         $receiver_email = $_POST['receiver_email']; //La cuenta que ha recibido el pago.
         $customer_email = $_POST['payer_email']; //La cuenta que ha enviado el pago.


         // Aqui verificamos si la cuenta que ha recibido el pago es nuestra cuenta.
         if($paypal_account != $receiver_email)
         {
            exit;
         }

         $res=file_get_contents($url."?".$req)
         ;
         if (strcmp (trim($res), "VERIFIED") == 0)
         {
                 // Verificamos si la moneda con la que se ha pagado es la misma que nosotros hemos configurado
            if($payment_currency != $paypal_currency)
            {
               exit;
            }

            if($payment_status == "Completed")
            {
               //El pago ha sido recibido y completado con éxito, entonces aqui colocamos el código que deseamos.
            }else
            {
                   //El pago ha sido recibido pero no esta completado, asi que pueden poner el pedido como pendiente.
            }
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

}
