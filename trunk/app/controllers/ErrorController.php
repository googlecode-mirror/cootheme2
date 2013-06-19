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
        $this->e404();
    }

    private function e404() {
        $data = array('title' => '404 not found');
        View::render('404.view.php', $data);
    }
}