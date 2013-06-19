<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 5/31/13
 * Time: 9:50 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class Coo_Mvc_Router_BaseRouter implements Coo_Mvc_Interface_Router {

    public static function route($request) {
        if($request instanceof Coo_Mvc_Request_BaseRequest) {
            $controller = $request->controller;
            $method = $request->method;
            $params = $request->params;
            $kontroller = new $controller;
            $kontroller->$method($params);
            return true;
        }
        return false;
    }
}