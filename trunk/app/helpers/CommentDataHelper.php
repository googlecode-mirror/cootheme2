<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khangvu
 * Date: 6/17/13
 * Time: 1:46 PM
 * To change this template use File | Settings | File Templates.
 */
class CommentDataHelper {

    public static function createCommentObject($comment = null){

        $params = array(
            'comment_content'=>get_comment_text($comment),
            'comment_author'=>get_comment_author($comment),
            'avatar'=> self::getAvatar($comment),
            'authorLink'=> get_comment_author_link($comment),
            'childrens'=>'',

        );

        $comment_object_var = get_object_vars($comment);

        $comment_params = ($params + $comment_object_var);

        return new Comment($comment_params);
    }



    private function getAvatar($comment){
        $size = array(50,100,200,300);
        $avatars = array();
        foreach($size as $val){
            preg_match("/src='(.*?)'/i", get_avatar($comment, $val ), $matches);
            $avatars[]= $matches[1];
        }

        return $avatars;
    }

    public static function buildCommentTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent == $parentId) {
                $children = self::buildCommentTree($elements, $element->id);
                if ($children) {
                    $element->childrens = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public static function getCommentsByPost($postId, $args = array()){
        $args['post_id'] = $postId;

        $comments = get_comments($args);

        if(is_array($comments)){
            $result = array();
            foreach($comments as $comment){
                $params = array(
                    'comment_content'=>get_comment_text($comment),
                    'comment_author'=>get_comment_author($comment),
                    'avatar'=> self::getAvatar($comment),
                    'authorLink'=> get_comment_author_link($comment),
                );

                $comment_object_var = get_object_vars($comment);

                $comment_params = ($params + $comment_object_var);

                $result[]= new Comment($comment_params);

            }
            return self::buildCommentTree($result);
        }else{
            return null;
        }
    }
}