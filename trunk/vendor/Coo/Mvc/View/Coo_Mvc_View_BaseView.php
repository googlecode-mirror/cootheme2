<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/4/13
 * Time: 1:29 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Coo_Mvc_View_BaseView implements Coo_Mvc_Interface_View {

    private static $layout = MASTER_LAYOUT ;

    public static function layout($_layout) {
        if(isset($_layout)) {
            self::$layout =  $_layout;
            return true;
        }
        return self::$layout;
    }

    public static function render($mainView, $data) {
        $file = LAYOUTS_PATH . self::$layout . '.php';
        if(is_readable($file)){
            if(is_array($data)){
                $data['mainView'] = $mainView;
                extract($data);
            }
            require $file;
        }
    }
}