<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khangvu
 * Date: 6/17/13
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */
class BaseEntity {

    protected $properties = array();

    public function __construct($properties = null){
        if(is_array($properties)){
               $this->properties = $properties;
        }
    }

    public function __destruct(){

    }

    public function __set($name, $value){
        // "Setting '$name' to '$value'\n";
        $this->properties[$name] = $value;
    }

    public function __get($name){
        // "Getting '$name'\n";
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

    /**  As of PHP 5.1.0  */
    public function __isset($name){
        // "Is '$name' set?\n";
        return isset($this->properties[$name]);
    }

    /**  As of PHP 5.1.0  */
    public function __unset($name){
        // "Unsetting '$name'\n";
        unset($this->properties[$name]);
    }

    public function initFromArray(array $properties){
        $this->properties = $properties;
    }

    public function initFromJson($jsonString){
        $this->properties = json_decode($jsonString,true);
    }

    public function initFromXml($xml, $from = "string"){
        if($from == "url" || $from == "file"){
            $xdom = simplexml_load_file($xml);
        }
        if($from == "string"){
            $xdom = simplexml_load_string($xml);
        }
        $this->properties = get_object_vars($xdom);
    }

    public function toArray(){
        return $this->properties;
    }

    public function toHtmlRow(){
        $html = null;
        $properties = $this->properties;
        if(count($properties)!= 0){
            $html = '<tr>';
            foreach($properties as $property){
                $html .= '<td>'.$property.'</td>';
            }
            $html .= '</tr>';
        }
        return $html;
    }

    public function toXml(){
        $xml = '';
        $properties = $this->properties;
        if(count($properties)!= 0){
            foreach($properties as  $property => $value){
                $xml .= "<$property>$value</$property>";
            }
        }
        return $xml;
    }

    public function toJson(){
        $json = '[{';
        $properties = $this->properties;
        if(count($properties)!= 0){
            foreach($properties as  $property => $value){
                $json .= '"'.$properties.'":"'.$value.'",';
            }
            $json = substr($json,0,-1);
        }
        $json .= ']}';
        return $json;
    }

}