<?php

class User extends BaseEntity {

    public function __construct($properties = null) {
        parent::__construct($this->convertNameProperties($properties));
    }

    public function convertNameProperties($properties){
        if(is_array($properties) && count($properties)!= 0){
            $result = array();
            foreach($properties as $property => $value){
                if($property == "ID"){
                    $new_property = "id";
                }else{
                    $property  = str_replace("user_","", $property);
                    $property_arr = explode("_", $property);
                    $new_property = "";
                    foreach($property_arr as $key => $val){
                        if($key == 0){
                            $new_property .= $val;
                        }else{
                            $new_property .= ucfirst($val);
                        }
                    }
                }
                $result[$new_property] = $value;
            }
            return $result;
        }else{
            return null;
        }
    }

}
