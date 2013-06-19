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
}