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


    /*

    public function getById($id){
        $user = get_userdata($id);
        if(is_object($user)){
            return new User(get_object_vars($user));
        }else{
            return null;
        }
    }

    public function getCurrentUser(){
        $current_user = wp_get_current_user();
        if(is_object($current_user)){
            return new User(get_object_vars($current_user->data));
        }else{
            return null;
        }
    }

    public function getByConditions(array $conditions){
        $result = array();
        foreach($conditions as $field => $value){
            $user = get_user_by($field, $value);
            if(is_object($user)){
                $result[] = new User(get_object_vars($user));
            }
        }
        return $result;
    }

    public function createUser($username, $pass, $email){
        if(!username_exists($username) && !email_exists($email)){
            return wp_create_user($username, $pass, $email);
        }else{
            return false;
        }
    }

    public function deleteUser($id){
        return wp_delete_user($id);
    }

    public function addUser($user_data){
        return wp_insert_user($user_data);
    }

    public function updateUser($user_data){
        return wp_update_user($user_data);
    }

    public function doQuery($query_string){
        global $wpdb;
        $rows = $wpdb->get_results($query_string, OBJECT);
        if(is_array($rows)){
            $result = array();
            foreach($rows as $row){
                $result[] = new User(get_object_vars($row));
            }
            return $result;
        }else{
            return null;
        }
    }


    */



}