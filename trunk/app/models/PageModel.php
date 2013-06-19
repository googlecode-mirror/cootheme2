<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khang
 * Date: 5/23/13
 * Time: 4:34 PM
 * To change this template use File | Settings | File Templates.
 */

class PageModel extends Coo_Mvc_Model_BaseModel {

    private $option = array('getComments'=>false);

    public function __construct(){
        parent::__construct();
    }

    public function get($args){
        $posts = new WP_Query($args);

        $result = array();
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $result[] = PageDataHelper::createPageObject($this->option['getComments']);
            }
        }
        wp_reset_postdata();
        return $result;
    }

    public function getAll(){
        $pages =  $this->get(array('post_type'=>'page', 'posts_per_page'=>-1));
        if(count($pages)!=0){
            return PageDataHelper::buildPageTree($pages);
        }else{
            return $pages;
        }
    }

    public function setOption($option, $value){
        $this->option[$option] = $value;
    }

    public function getOption(){
        return $this->option;
    }

    public function getByAuthorId($author_id, $number = -1){
        $args = array(
            'posts_per_page'=>$number,
            'post_type'=>'page',
        );

        $args['author'] = $author_id ;

        return $this->get($args);
    }

    public function getByAuthorName($name, $number = null){
        $args = array();
        if($number != null){
            $args['posts_per_page'] = $number;
        }
        $args['author_name'] = $name ;
        $args['post_type'] = 'page';

        return $this->get($args);
    }

    public function getById($id, $getComments = false){
        return $this->get('page_id='.$id, $getComments);
    }

    public function getBySlug($slug, $getComments = false){
        return $this->get('pagename='.$slug, $getComments);
    }

    public function search($keyword, $number = -1){
        return $this->get(array('s'=>$keyword, 'post_type'=>'page', 'posts_per_page'=>$number));
    }

    public function getByPath($path, $getComments = false){
        return $this->get('pagename='.$path, $getComments);
    }

    public function getByTitle($title, $getComments = false){
        return PageDataHelper::createPageObject($getComments ,get_page_by_title($title));
    }


    public function getPageChildren($id = 0){
        $all_pages = get_posts(array('post_type'=>'page'));
        $page_children = get_page_children($id, $all_pages);


        if(is_array($page_children)){
            $page_children_obj = array();
            foreach($page_children as $child){
                $page_children_obj[] = PageDataHelper::createPageObject(false, $child);
            }
            return $page_children_obj;

        }else{
            return null;
        }
    }

    public function getPageTree($root_id = 0){
        $page_arr = $this->getAll();
        return PageDataHelper::buildPageTree($page_arr, $root_id);
        //return $page_arr;
    }






    /*
    public function __construct(){
        parent::__construct();
    }

    public function getBySlug($slug){
        return $this->get('pagename='.$slug);
    }

    public function getById($id){
        return $this->get('page_id='.$id);
    }

    public function getAll($orderby = 'date', $order='DESC'){
        return $this->get(array('post_type'=>'page', 'posts_per_page'=>-1, 'orderby'=>$orderby, 'order'=>$order));
    }

    public function search($keyword, $number = -1){
        return $this->get(array('s'=>$keyword, 'posts_per_page'=>$number));
    }



    private function createPageObject(){
        global $post;

        $params = array(
            'permalink' => get_permalink(),
            'post_title' => get_the_title(),
            'time' => get_the_time(),
            'post_content' => $this->getTheContent(),
            'post_excerpt' => get_the_excerpt(),
            'post_author' => get_the_author(),
            'author_link' => get_the_author_link(),
            'thumbnails'=> $this->getThumbnails($post),
            //'page_uri'=> get_page_uri($post->ID),

        );
        $post_object_var = get_object_vars($post);

        $post_params = ($params + $post_object_var);

        //echo '<pre>';
        //print_r(($post_params));
        //echo '</pre>';
        //exit();
        return new Page($post_params);
    }

    public function get($args){

        $posts = new WP_Query($args);

        $result = array();
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $result[] = $this->createPageObject();
            }
        }
        wp_reset_postdata();
        return $result;
    }



    public  function getThumbnails($post) {
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

    public  function getTheContent(){
        global $post;
        $content = apply_filters('the_content', get_the_content());
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }



    /*
    public function getPageUrl($id){
       return _get_page_link($id);
    }


    public function getByConditions($conditions = '', $post_type = 'page'){
        $result = array();
        if($conditions == '' || $conditions == null){
            $conditions = 'post_type = '.$post_type;
        }
        elseif(is_array($conditions)){
            if(!isset($conditions['post_type'])){
                $conditions['post_type'] = $post_type;
            }
        }
        elseif(is_string($conditions)){
            if(preg_match('post_type=',$conditions) != 1){
                $conditions .= '&post_type = '.$post_type;
            }
        }

        $pages = get_posts($conditions);
        if(is_array($pages)){
            foreach($pages as $page){
                $result[] = new Page(get_object_vars($page));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getById($id){
        $page = get_post($id);
        if(is_object($page)){
            return  new Page(get_object_vars($page));
        }else{
            return null;
        }
    }

    public function getAllPages(){
        $args = array('post_type'=>'page');
        $pages = query_posts($args);
        if(is_array($pages)){
            foreach($pages as $page){
                $result[] = new Page(get_object_vars($page));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getPageByPath($page_path){
        $page = get_page_by_path($page_path, OBJECT);
        if(is_object($page)){
            return new Page (get_object_vars($page));
        }else{
            return null;
        }
    }

    public function getAllPageIds(){
        return get_all_page_ids();
    }

    public function getPageByTitle($page_title){
        $page = get_page_by_title($page_title, OBJECT);
        if(is_object($page)){
            return new Page (get_object_vars($page));
        }else{
            return null;
        }
    }

    public function getPageLink($page_id, $leavename = false, $sample = false){
        return get_page_link($page_id, $leavename, $sample);
    }

    public function getPageUri($page_id){
        return get_page_uri($page_id);
    }

    public function getPageChildren($id = 0){
        $all_pages = get_posts(array('post_type'=>'page'));
        $page_children = get_page_children($id, $all_pages);


        if(is_array($page_children)){
            $page_children_obj = array();
            foreach($page_children as $child){
                $page_children_obj[] = new Page(get_object_vars($child));
            }
            return $page_children_obj;

        }else{
            return null;
        }
    }

    public function getPageTree($root_id = 0){
        $all_pages = get_posts(array('post_type'=>'page'));

        if(is_array($all_pages)){
            $page_arr = array();
            foreach($all_pages as $page){
                $page_arr[] = get_object_vars($page);
            }
            return $this->buildPageTree($page_arr, $root_id);

        }else{
            return null;
        }
    }

    public function buildPageTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['post_parent'] == $parentId) {
                $children = $this->buildPageTree($elements, $element['ID']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function insert($page_array, $return_error = 'false'){
        return wp_insert_post($page_array, $return_error);
    }

    public function update($page_array, $return_error = 'false'){
        return wp_update_post($page_array, $return_error);
    }

    public function delete($page_id, $force='false'){
        return wp_delete_post($page_id);
    }

    public function publish($page_id){
        return wp_publish_post($page_id);
    }

    public function moveToTrash($page_id){
        return wp_trash_post($page_id);
    }

    public function doQuery($query_string){
        global $wpdb;
        $rows = $wpdb->get_results($query_string, OBJECT);
        if(is_array($rows)){
            $result = array();
            foreach($rows as $row){
                $result[] = new Page(get_object_vars($row));
            }
            return $result;
        }else{
            return null;
        }
    }
    */

}