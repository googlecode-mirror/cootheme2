<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khangvu
 * Date: 6/17/13
 * Time: 10:02 AM
 * To change this template use File | Settings | File Templates.
 */
class PostDataHelper {

    public static function createPostObject( $getComments = false, $postObject = null){
        global $post;
        if($postObject != null){
            setup_postdata( $postObject);
        }
        $tags = get_the_tags();
        $categories = get_the_category();

        $params = array(
            'permalink' => get_permalink(),
            'post_title' => get_the_title(),
            'time' => get_the_time(),
            'post_content' => self::getTheContent(),
            'post_excerpt' => get_the_excerpt(),
            'post_author' => get_the_author(),
            'author_link' => get_the_author_link(),
            'tags' => self::getTags($tags),
            'categories' => self::getCategories($categories),
            'thumbnails'=> self::getThumbnails($post),
            'comments'=>'',

        );
        if($getComments != false){
            $params['comments'] = self::getComments($post->ID);
        }
        $post_object_var = get_object_vars($post);

        $post_params = ($params + $post_object_var);

        //echo '<pre>';
        //print_r($post_params);
        //echo '</pre>';
        //exit();
        return new Post($post_params);
    }

    public static function getComments($postId,  $args = array()){
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
            return CommentDataHelper::buildCommentTree($result);
        }else{
            return null;
        }
    }

    public function getCategories($theCategories) {
        if (!$theCategories) {
            return array();
        }
        $array = array();

        foreach ($theCategories as $category) {
            $params = array(
                'name' => get_cat_name($category->cat_ID),
                'link'=> get_category_link($category),
            );
            $array[] = new Category($params + get_object_vars($category));
        }
        return $array;

    }

    private function getThumbnails($post) {
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

    private  function getTheContent(){
        global $post;
        $content = apply_filters('the_content', get_the_content());
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }

    private  function getTags($theTags) {
        if (!$theTags) {
            return array();
        }
        $array = array();

        foreach ($theTags as $tag) {
            $tagAsArray = get_object_vars($tag);
            $tagAsArray['tag_link'] = get_tag_link($tag->term_id);
            array_push($array, $tagAsArray);
        }

        return $array;
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
}