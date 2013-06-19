<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/18/13
 * Time: 11:56 PM
 * To change this template use File | Settings | File Templates.
 */

class ErrorController extends Coo_Controller_BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index($params=null) {
        echo "404 Not found! <br><hr>The resource you requested is not found on this server";
    }


}