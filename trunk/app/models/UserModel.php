<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khang
 * Date: 5/24/13
 * Time: 9:47 AM
 * To change this template use File | Settings | File Templates.
 */

class UserModel extends Coo_Mvc_Model_BaseModel {

    public function __construct(){

    }

    public function get($args){
        $result = array();
        $users = new WP_User_Query($args);
        if(count($users->results)!= 0){
            foreach($users->results as $user){
                $result[] = $this->createUserObject($user);
            }
        }
        return $result;
    }

    public function getAll(){
        return $this->get(array( 'number'=>''));
    }

    public function createUserObject($user){
        $params = array(

            'numberPost'=>count_user_posts($user->ID),
        );
        $user_params_arr = $params + get_object_vars($user);

        return new User($user_params_arr);

    }


    public function getCurrentUser(){
        $current_user = wp_get_current_user();
        if(is_object($current_user)){
            return $this->createUserObject($current_user);
        }else{
            return null;
        }
    }

    public function getByField($field, $value){
        $result = array();

        $user = get_user_by($field, $value);
        if(is_object($user)){
            $result[] = $this->createUserObject($user);
        }

        return $result;
    }

    public function getById($id){
        $user = get_userdata($id);
        if(is_object($user)){
            return $this->createUserObject($user);
        }else{
            return null;
        }
    }
}