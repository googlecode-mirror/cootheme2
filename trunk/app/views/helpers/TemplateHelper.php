<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 6/19/13
 * Time: 1:40 PM
 * To change this template use File | Settings | File Templates.
 */

class TemplateHelper {

    public static function getTemplateImageUrl() {
        return bloginfo('template_url') . '/assets/app/img/';
    }

    public static function getLayoutStyleUrl() {
        $layout = View::layout(null);
        return bloginfo('template_url') . '/assets/app/css/layouts/' . $layout . '.css';
    }

    public static function getTwitterBootstrapUrl() {
        return bloginfo('template_url') . '/assets/vendor/bootstrap/twitter/';
    }
}