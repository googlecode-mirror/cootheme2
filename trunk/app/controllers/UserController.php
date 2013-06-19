<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/19/13
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */

class UserController extends Coo_Controller_BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index($params=null) {
        echo "This is UserController";
    }
}