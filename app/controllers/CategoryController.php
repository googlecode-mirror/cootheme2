<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/19/13
 * Time: 12:09 AM
 * To change this template use File | Settings | File Templates.
 */

class CategoryController extends Coo_Controller_BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index($params=null) {
        echo "This is CategoryController";
    }

}