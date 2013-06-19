<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/18/13
 * Time: 11:16 PM
 * To change this template use File | Settings | File Templates.
 */

class Request extends Coo_Mvc_Request_BaseRequest {

    public function __construct() {
        parent::__construct();
    }

    public function controller() {
        if($this->is404()) {
            return "ErrorController";
        }
        if($this->isHome()) {
            return "HomeController";
        }
        if($this->isCategory()) {
            return "CategoryController";
        }
        if($this->isPage()) {
            return "PageController";
        }
        if($this->isAuthor()) {
            return "AuthorController";
        }
        if($this->isArchive()) {
            return "PostController";
        }
        return 'ErrorController';
    }
}