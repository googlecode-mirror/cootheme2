<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quangvu
 * Date: 5/31/13
 * Time: 9:50 AM
 * To change this template use File | Settings | File Templates.
 */
abstract class Coo_Mvc_Request_BaseRequest implements Coo_Mvc_Interface_Request {

    protected  $properties = array();

    public function __construct() {
        $this->make();
    }

    public function make() {
        $this->properties['controller'] = $this->controller();
        $this->properties['method'] = $this->method();
        $this->properties['params'] = $this->params();
    }

    abstract public function controller();

    public function method() {
        return 'index';
    }

    public function params() {
        global $wp_query;
        return $wp_query->query;
    }

    public function isHome() {
        global $wp_query;
        return $wp_query->is_home;
    }

    public function isArchive() {
        global $wp_query;
        return $wp_query->is_archive;
    }

    public function isPage() {
        global $wp_query;
        return $wp_query->is_page;
    }

    public function isCategory() {
        global $wp_query;
        return $wp_query->is_category;
    }

    public function isAuthor() {
        global $wp_query;
        return $wp_query->is_author;
    }

    public function isTag() {
        global $wp_query;
        return $wp_query->is_tag;
    }

    public function isFeed() {
        global $wp_query;
        return $wp_query->is_feed;
    }

    public function is404() {
        global $wp_query;
        return $wp_query->is_404;
    }

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __isset($name) {
        return isset($this->properties[$name]);
    }

    public function __unset($name) {
        unset($this->properties[$name]);
    }

    public function __destruct() {
        unset($this->properties);
    }
}
