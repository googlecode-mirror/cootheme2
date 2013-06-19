<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khang
 * Date: 5/24/13
 * Time: 9:21 AM
 * To change this template use File | Settings | File Templates.
 */



class CommentModel extends  Coo_Mvc_Model_BaseModel {

    public function __construct(){

    }

    public function get($args) {
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = CommentDataHelper::createCommentObject($comment);
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getAll(){
        $comments = $this->get(null);
        if(count($comments)!=0){
            return CommentDataHelper::buildCommentTree($comments);
        }else{
            return $comments;
        }
    }

    public function getOnPost($postId , $args = array()){
        $args['post_id'] = $postId;

        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = CommentDataHelper::createCommentObject($comment);
            }
            return CommentDataHelper::buildCommentTree($result);
        }else{
            return null;
        }
    }


    public function getByUser($user_id, $number = '', $args = array()){
        $args['user_id'] = $user_id;
        $args['number'] = $number;
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = CommentDataHelper::createCommentObject($comment);
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getRecentComments($number = 10, $args = array()){
        $args['number'] = $number;
        $args['status'] = 'approve';
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = CommentDataHelper::createCommentObject($comment);
            }
            return $result;
        }else{
            return null;
        }
    }

    public function countComment($postId){
        return get_comments_number($postId);
    }




    /*
    public function getById($id){
        $comment = get_comment($id);
        if(is_object($comment)){
            return new Comment(get_object_vars($comment));
        }else{
            return null;
        }
    }

    public function getCommentByPost($post_id ,$number = '', $args = array()){
        $args['post_id'] = $post_id;
        $args['number'] = $number;
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = new Comment(get_object_vars($comment));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getRecentComment($number = 10, $conditions = array('status'=>'approve')){
        $coditions['number'] = $number;
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = new Comment(get_object_vars($comment));
            }
            return $result;
        }else{
            return null;
        }
    }



    public function getCommentByUser($user_id, $number = '', $args = array()){
        $args['user_id'] = $user_id;
        $args['number'] = $number;
        $comments = get_comments($args);
        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $result[] = new Comment(get_object_vars($comment));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getCommentAuthor($comment_id){
        return get__comment_author($comment_id);
    }

    public function getCommentNumber($post_id){
        return get_comments_number($post_id);
    }

    public function insert($comment_data){
        return wp_insert_comment($comment_data);
    }

    public function update($comment_data){
        return wp_update_comment($comment_data);b
    }

    public function delete($comment_id, $force='false'){
        return wp_delete_comment($comment_id, $force='false');
    }



    public function doQuery($query_string){
        global $wpdb;
        $rows = $wpdb->get_results($query_string, OBJECT);
        if(is_array($rows)){
            $result = array();
            foreach($rows as $row){
                $result[] = new Comment(get_object_vars($row));
            }
            return $result;
        }else{
            return null;
        }
    }

    */

}