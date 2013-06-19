<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/4/13
 * Time: 2:00 PM
 * To change this template use File | Settings | File Templates.
 */

class Logger {

    private function __construct() {}

    public static function eLog($message) {
        echo "<br>$message<br>";
    }

    public static function pLog($o) {
        print "<pre>";
        print_r($o);
        print "</pre>";
    }
}