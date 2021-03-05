<?php 
/**
 * PrestaShop module created by Arturo
 *
 * @author    arturo https://artulance.com/
 * @copyright 2020-2021 arturo
 * @license   This program is  free software but you can't resell it
 *
 * CONTACT WITH DEVELOPER
 * artudevweb@gmail.com
 */

include_once(_PS_MODULE_DIR_.'cupon/classes/listado.php');

class cupon extends Module{

    const CART_RULE_PERCENT = 1;
    const CART_RULE_AMOUNT = 2;
    const CART_RULE_FREE_SHIPPING = 3;
    const PREFIX_DISCOUNT = 'Cupon';
    const DISCOUNT_AMOUNT = 10; //5 porcentaje o euro
    const APPLIED_AUTO = false; //aplica automaticamente
  //  private $codigocupon;


    public function __construct()
    {
        $this->name          = 'cupon';
        $this->tab           = 'Blocks';
        $this->author        = 'artulance.com';
        $this->version       = '1.0.0';
        $this->bootstrap     = true;
        //le indicamos que lo construya
        /*El logo no hace falta definirlo, lo coge automáticamente de la carpeta si lo llamas logo.png */
        parent::__construct();
        $this->displayName = $this->l('Cupon de descuento');
        $this->description = $this->l('Este modulo genera un cupon para el usuario');


        $this->js_path = $this->_path . 'views/js/';
        $this->css_path = $this->_path . 'views/css/';
        $this->module_path = $this->_path;
    }

    public function install()
    {
        if(!parent::install() 
        || !$this->installDB() 
        || !$this->registerHook('displayOrderConfirmation') 
        || !$this->registerHook('DisplayHeader') 
        || !$this->registerHook('actionValidateOrder'))
        {
            /* Como comprobación si no está instalado o si esta registrado en el hook de la home o en el hook del footer,devolverá false
             /* || !$this->registerHook($this->getHooks()))*/ 
            return false;
        }else{
            //si esta bien instalado nos dirá que es true
            return true;
        }
    }

    public function getHooks(){
        return array(
            'displayOrderConfirmation', // Se muestra cuando el pedido ha sido confirmado
            'actionValidateOrder', //Se activa cuando el pedido ha sido validado
        );
    }

    public function unistall()
    {
     /*   if(!parent::unistall() || ! $this->uninstallDB() || !$this->unregisterHook($this->getHooks()))*/
        if(!parent::unistall() 
        || ! $this->uninstallDB() 
        || !$this->unregisterHook('displayOrderConfirmation')
        || !$this->unregisterHook('DisplayHeader') 
        || !$this->unregisterHook('actionValidateOrder'))
        {
                /* Como comprobación si no está desinstalado o si esta registrado en el hook de la home o en el hook del footer,devolverá false */
            return false;
        }else{
            return true;
        }
    }
    public function installDB()
    {
        $result = Db::getInstance()->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cupondescuento` (
                `id_cupon` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_usuario` int(11) unsigned NOT NULL DEFAULT '1',
                `id_cart` BIGINT unsigned NOT NULL DEFAULT '1',
                `nombre` text,
                `apellidos` text,
                `email` text,
                `code` varchar(254) DEFAULT NULL,
                `date_add` datetime DEFAULT NULL,
                `date_upd` datetime DEFAULT NULL,
                PRIMARY KEY (`id_cupon`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );
        /** probar a instalar y si no funciona, quitar lo de mysql_engine que de default esta puesto como innodb */
        return $result;
    }
    public function uninstallDB()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'cupondescuento`');
    }


    /* Función que solo es necesaria si hay algo que configurar del módulo, en este caso si */
    public function getContent()
    {
        $this->loadAsset();
        return $this->postProcess() . $this->getForm();
    }

    public function getForm()
    {
        $moduleAdminLink = $this->context->link->getAdminLink('AdminModules', true, false, ['configure' => $this->name]);

        $this->context->smarty->assign([
            'customer_link' => $this->context->link->getAdminLink('AdminCustomers', true) . '&viewcustomer&id_customer=',
            'module_name' => $this->name,
           
            'module_version' => $this->version,
            'moduleAdminLink' => $moduleAdminLink,
      
            'cupones' => Listado::getCupones(),
            'languages' => $this->context->controller->getLanguages(),
            'defaultFormLanguage' => (int) $this->context->employee->id_lang,
            
            'ps_base_dir' => Tools::getHttpHost(true),
            'ps_version' => _PS_VERSION_,
            'cantidaddescuento' => Configuration::get('CANTIDAD_MODULO_CUPON'),
            'cantidadcupon' => Configuration::get('PORCENTAJE_MODULO_CUPON'),
        ]);

       return $this->output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/menu.tpl');
    }

    public function postProcess()
    {
        /* El submit con lo que hayamos configurado el campo en el getform */
        if (Tools::isSubmit('save')) {
            $cantidad_descuento = Tools::getValue('cantidad_descuento');
            $porcentaje_descuento = Tools::getValue('porcentaje_descuento');
        /* Cogemos el texto de la tabla ps_configuration con su campo correspondiente para poner en el value*/
            Configuration::updateValue('CANTIDAD_MODULO_CUPON', $cantidad_descuento);
            Configuration::updateValue('PORCENTAJE_MODULO_CUPON', $porcentaje_descuento);
            /* Devuelvo un mensaje de confirmación si se actualiza adecuadamente */
            return $this->displayConfirmation($this->l('Updated Successfully'));
        }
    }

    /*Puedo configurar que si no se registra en los hooks en el install, pueda meterlo en el hook de displayhome manualmente poniendo esta funcion
    https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/cupon.css', 'all');
        $this->context->controller->addJS($this->_path.'views/js/scratch.js', 'all');
        //return $this->loadAsset();
    }

    //aquí lo muestra en la pagina de orderconfirmation
    public function hookdisplayOrderConfirmation($params)
    {
       /* $this->debugvardump($params);*/
     //  $this->debug('SELECT code FROM '._DB_PREFIX_.'cupondescuento WHERE id_usuario="'.$params['cart']->id_customer.'"');
     if($codigocupon=Db::getInstance()->getValue('SELECT code FROM '._DB_PREFIX_.'cupondescuento WHERE id_usuario="'.$params['cart']->id_customer.'" and id_cart="'.$params['order']->id_cart.'"'))
     {
        $this->context->smarty->assign(array(
            'texto_variable' => $codigocupon,
        ));
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/order-confirmation.tpl');
     }

       
    }
    /* Aqui se activa cuando va a orderconfirmation, es decir, se valida(obviamente) y aquí hacemos el grueso de generar el cupón y mandar el email */
    public function hookactionValidateOrder($params)
    {
        $this->createcupon($params);
       // $this->debugvardump($params);
    }

    //nothing
    public function hookOrderConfirmation($params)
    {       
    
    }
    //aqui lo muestra en la pagina de usuario y mira su pedido
    public function hookdisplayOrderDetail($params)
    {       
     
    }

  /**
     * load dependencies in the configuration of the module
     */
    public function loadAsset()
    {
        // Load CSS
        $css = [
            $this->css_path . 'fontawesome-all.min.css',
            $this->css_path . 'back.css',
            $this->css_path . $this->name . '.css',
        ];

        $this->context->controller->addCSS($css, 'all');

        // Load JS
        $jss = [
            $this->js_path . 'menu.js',
        ];

        $this->context->controller->addJS($jss);

        // Clean memory
        unset($jss, $css);
    }

    public function enviarmail($plantilla,$user_email,$nombre,$apellido,$cantidad_descuento,$codigo_cupon,$cantidad_gastada,$asunto)
    {

        $email_data = array('{firstname}' => $nombre, '{lastname}' => $apellido, '{cupon_amount}' => $cantidad_descuento, '{cupon_codigo}' => $codigo_cupon, '{cantidad_gastada}' => $cantidad_gastada);
        Mail::Send((int) Configuration::get('PS_LANG_DEFAULT'),
         $plantilla, Mail::l($asunto, 
         (int) Configuration::get('PS_LANG_DEFAULT')), 
         $email_data, $user_email, null, 
         (string) Configuration::get('PS_SHOP_EMAIL'), 
         (string) Configuration::get('PS_SHOP_NAME'), 
         null, 
         null, 
         dirname(__FILE__) . '/mails/');

    }

    public function createcupon($params)
    {

        $user_email=$params['customer']->email;
        $id_usuario=$params['customer']->id;
        $nombre=$params['customer']->firstname;
        $apellido=$params['customer']->lastname;
        $id_carrito=$params['cart']->id;

        $cantidad_gastada=Db::getInstance()->getValue('SELECT ROUND(sum(po.total_paid),2) as totalpaid FROM `ps_orders` po where id_customer="'.pSQL($id_usuario).'"');
        $cantidad_configurada=Configuration::get('CANTIDAD_MODULO_CUPON');

        $sendEmail=true;
        $cupon = new CartRule();
      //  $cupon->id_customer = (int) ($user->id);
        $cupon->id_customer = (int) ($id_usuario);
        $discount_amount = (Configuration::hasKey('PORCENTAJE_MODULO_CUPON')) ? (int) Configuration::get('PORCENTAJE_MODULO_CUPON') : (int) Discountonfirstorder::DISCOUNT_AMOUNT;
       /* $cupon->id_discount_type = (Configuration::hasKey('DISCOUNTONFIRSTORDER_DISCOUNT_TYPE')) ? (int) Configuration::get('DISCOUNTONFIRSTORDER_DISCOUNT_TYPE') : (int) Discountonfirstorder::CART_RULE_PERCENT;*/

       $cupon->reduction_percent = $discount_amount;
        $cart_rule_name = $this->l('Cupon por gaston ') . $discount_amount . '% - Ref: ' . (int) ($cupon->id_customer) . ' - ' . date('Y');
        array('1' => $cart_rule_name, '2' => $cart_rule_name);
        $languages = Language::getLanguages();
        $array_name = array();
        foreach ($languages as $language) {
            $array_name[$language['id_lang']] = $cart_rule_name;
        }
        $cupon->name = $array_name;
        $cupon->description = $this->l('¡Cupón por llegar a una determinada cantidad!');
        $cupon->id_currency = Configuration::get('PS_CURRENCY_DEFAULT'); /* Old */
        $cupon->quantity = 1;
        $cupon->code=$this->generatecode();
        $cupon->quantity_per_user = 1;
        $cupon->reduction_tax = 1; // impuestos incluidos
        $cupon->partial_use = false;
        $cupon->product_restriction = false;
        $cupon->cart_rule_restriction = true; /* No acumulable */
        $cupon->date_from = date('Y-m-d');
        $cupon->date_to = strftime('%Y-%m-%d', strtotime('+2 year'));
        $cupon->minimum_amount = 0;
        $cupon->active = true;
      
        if($cantidad_gastada>$cantidad_configurada){
           // $this->debug("Llega a la cantidad".$cantidad_configurada);
            if(!$id_cupon=Db::getInstance()->getValue('SELECT id_cupon FROM '._DB_PREFIX_.'cupondescuento WHERE id_usuario="'.$id_usuario.'"'))
            {
               // $this->debug("No esta en la base de datos".$id_usuario.'SELECT id_cupon FROM '._DB_PREFIX_.'cupondescuento WHERE id_usuario="'.$id_usuario.'"');
                if ($cupon->add()) {
                    if ($sendEmail) {
                    //    $this->debug("Enviado el mail".$user_email);
                        $this->insertarcupon($id_usuario,$nombre,$apellido,$user_email,$cupon->code,date('Y-m-d H:i:s'),$id_carrito);
                        $this->enviarmail("firstorder",$user_email,$nombre,$apellido,$discount_amount,$cupon->code,$cantidad_gastada,"¡Cupón de descuento!");
                    }
                } else {
                    echo Db::getInstance()->getMsgError();
                } 
            }else{
                $this->enviarmail("gaston",$user_email,$nombre,$apellido,$discount_amount,$cupon->code,$cantidad_gastada,"¡Gaston!");
            }
    }else{
       // $this->debug("No llega a la cantidad".$cantidad_gastada);
        $this->enviarmail("gaston",$user_email,$nombre,$apellido,$discount_amount,$cupon->code,$cantidad_gastada,"¡Gaston!");
    }
         

     //   $this->debug($cupon->code);
      


    }

    public function insertarcupon($id_usuario,$nombre,$apellido,$email,$cupon,$date_add,$id_carrito)
    {
        Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."cupondescuento` (`id_usuario`,`id_cart`, `nombre`, `apellidos`, `email`, `code`, `date_add`, `date_upd`) VALUES ( $id_usuario,'$id_carrito','$nombre','$apellido','$email','$cupon', '$date_add','$date_add') " );

    }

        public function generatecode()
        {
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
        }

    public function debug($texto){
        $logfilename = dirname(__FILE__).'/log.log';
        file_put_contents($logfilename, date('M d Y G:i:s') . ' -- ' . $texto . "\r\n", is_file($logfilename)?FILE_APPEND:0);
    }
    public function debugvardump($textovardump){
    
     $texto = print_r($textovardump, true);
        $logfilename = dirname(__FILE__).'/logdump.log';
        file_put_contents($logfilename, date('M d Y G:i:s') . ' -- ' . serialize($texto) . "\r\n", is_file($logfilename)?FILE_APPEND:0);
    }

}



?>