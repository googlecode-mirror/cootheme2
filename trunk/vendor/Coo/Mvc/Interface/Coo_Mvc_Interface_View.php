<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/18/13
 * Time: 10:11 AM
 * To change this template use File | Settings | File Templates.
 */

interface Coo_Mvc_Interface_View {

    public static function layout($layout);

    public static function render($template, $data);

}