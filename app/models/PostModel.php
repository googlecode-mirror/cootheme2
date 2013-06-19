<?php
class PostModel extends Coo_Mvc_Model_BaseModel {

    private $option = array('getComments'=>false);

    public function __construct(){
        parent::__construct();

    }

    public function get($args ){
        $posts = new WP_Query($args);
        $result = array();
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $result[] = PostDataHelper::createPostObject($this->option['getComments']);
            }
        }
        wp_reset_postdata();
        return $result;
    }

    public function getAll(){
        return $this->get(array('post_type'=>'post', 'posts_per_page'=>-1));
    }

    public function setOption($option, $value){
        $this->option[$option] = $value;
    }

    public function getOption(){
        return $this->option;
    }

    public function getRecentPost($number){
        return $this->get('posts_per_page='.$number);
    }

    public function getByAuthorId($author_id, $number = null){
        $args = array();
        if($number != null){
            $args['postsComment = _per_page'] = $number;
        }
        $args['author'] = $author_id ;
        
        return $this->get($args);
    }

    public function getByAuthorName($name, $number = null){
        $args = array();
        if($number != null){
            $args['posts_per_page'] = $number;
        }
        $args['author_name'] = $name ;

        return $this->get($args);
    }

    public function getByCategoriesId( $categories, $number = -1, $extra = 'in'){
        if($extra != 'in'){
            if(!is_array($categories)){
                $cate_array = explode(',',$categories);
            }else{
                $cate_array = $categories;
            }
            $args = array(
                'category__'.$extra => $cate_array,
                'posts_per_page' => $number,
            );
        }else{
            $args = array(
                'cat' => $categories,
                'posts_per_page' => $number,
            );
        }
        return $this->get($args);
    }


    public function getByCategoriesSlug($slug, $number = -1 ){
        $args = array(
            'category_name' => $slug,
            'posts_per_page' => $number,
        );
        return $this->get($args);
    }

    public function getByTagsId( $tags, $number = -1, $extra = 'in'){
        if($extra != 'in'){
            if(!is_array($tags)){
                $cate_array = explode(',',$tags);
            }else{
                $cate_array = $tags;
            }
            $args = array(
                'tag__'.$extra => $cate_array,
                'posts_per_page' => $number,
            );
        }else{
            $args = array(
                'tag_id' => $tags,
                'posts_per_page' => $number,
            );
        }
        return $this->get($args);
    }

    public function getByTagsSlug($slug, $number = -1 ){
        $args = array(
            'tag' => $slug,
            'posts_per_page' => $number,
        );
        return $this->get($args);
    }

    public function getById($id, $getComment = false){
        return $this->get('page_id='.$id, $getComment);
    }

    public function getBySlug($slug, $getComment = false){
        return $this->get('name='.$slug, $getComment);
    }

    public function search($keyword, $number = -1){
        return $this->get(array('s'=>$keyword, 'posts_per_page'=>$number,'post_type'=>'post'));
    }


    public function getByDate($year = null, $monthnum = null, $day = null, $conditions = array()){

        $conditions['year'] = !is_null($year) ? $year : date('Y');
        $conditions['monthnum'] = !is_null($monthnum) ? $monthnum : date('m');
        $conditions['day'] = !is_null($day) ? $day : date('d');

        $posts = query_posts($conditions);
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = PostDataHelper::createPostObject($this->option['getComments']);
            }
            return $result;
        }else{
            return null;
        }
    }
}