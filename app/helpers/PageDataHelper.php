<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khangvu
 * Date: 6/17/13
 * Time: 10:28 AM
 * To change this template use File | Settings | File Templates.
 */
class PageDataHelper {


    public static function createPageObject($getComments = false, $postObject = null ){
        global $post;
        if($postObject != null){
            setup_postdata( $postObject);
        }
        $params = array(
            'pageLink' => get_page_link(),
            'post_title' => get_the_title(),
            'time' => get_the_time(),
            'post_content' => apply_filters('the_content', get_the_content()),
            'post_excerpt' => get_the_excerpt(),
            'post_author' => get_the_author(),
            'author_link' => get_the_author_link(),
            'thumbnails'=> self::getThumbnails($post),
            'pageUri'=> get_page_uri($post),
            'comments'=>'',
        );

        if($getComments != false){
            $params['comments'] = self::getComments($post->ID);

        }
        $post_object_var = get_object_vars($post);

        $post_params = ($params + $post_object_var);
        return new Page($post_params);
    }

    public static function buildPageTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent == $parentId) {
                $children = self::buildPageTree($elements, $element->id);
                if ($children) {
                    $element->childrens = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    private  function getThumbnails($post) {
        global $post;
        $sizes = array('thumbnail', 'medium', 'large', 'full');
        $thumbnails = array();
        foreach ($sizes as $size) {
            $imageObject = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size);
            if (!empty($imageObject)) {
                $thumbnails[$size] = $imageObject[0];
            }
        }
        return $thumbnails;
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

    public static function getComments($postId, $args = array()){
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
            return CommentDataHelper::buildCommentTree($result);;
        }else{
            return null;
        }
    }
}