<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/4/13
 * Time: 1:47 PM
 * To change this template use File | Settings | File Templates.
 */

class HomeController extends Coo_Controller_BaseController {

    public function __construct() {
        parent::__construct();
        $this->init();
    }

    private function init() {
    }

    public function index($params=null) {
        $this->home();
    }

    private function home() {
        $data=array();
        $data['title'] = "View demo";
        View::layout('master');
        View::render('home.view.php', $data );
    }
}