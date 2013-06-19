<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/19/13
 * Time: 1:32 AM
 * To change this template use File | Settings | File Templates.
 */

class PostController extends Coo_Controller_BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index($params=null) {
        echo "this is PostController <hr>";
    }
}