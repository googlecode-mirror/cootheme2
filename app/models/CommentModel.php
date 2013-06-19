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
        parent::__construct();
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
}