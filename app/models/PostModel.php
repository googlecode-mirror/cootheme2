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




    /*
    public function  getPostFields($fields, $post_id){
        if(is_array($fields)){
            $result = array();
            foreach($fields as $field){
                $result[$field] = get_post_field($field, $post_id);
            }
            return $result;
        }else{
            return get_post_field($fields, $post_id);
        }
    }

    public function getByConditions($conditions = ''){
        $result = array();

        $posts = query_posts($conditions);
        if(is_array($posts)){
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getByCategories($category_id, $posts_per_page = -1, $paged =1,  $order = array()){

        $conditions =array(
            'posts_per_page'   => $posts_per_page, // get all posts.
            'paged'=> $paged,
            'tax_query'     => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'id',
                    'terms'     => is_array($category_id) ? $category_id : array($category_id),
                ),
            ),
        );
        if(count($order) != 0){
            foreach($order as $key => $value){
                $conditions['orderby'] = $key;
                $conditions['order'] = $value;
            }
        }
        return $this->getByConditions($conditions);
    }

    public function getAllByCategory($category_id, $order = array()){
        return $this->getAllByCategory($category_id, -1, 0, $order);
    }

    public function getByAuthor($author_id, $posts_per_page = -1, $paged = 1, $order = array()){
        $conditions =array(
            'posts_per_page'   => $posts_per_page, // get all posts.
            'paged'=> $paged,
            'author'=>$author_id,
        );
        if(count($order) != 0){
            foreach($order as $key => $value){
                $conditions['orderby'] = $key;
                $conditions['order'] = $value;
            }
        }
        return $this->getByConditions($conditions);
    }

    public function getByTag($tag, $posts_per_page = -1 , $paged = 1, $order = array(), $and = false){
        $args=array(
            'posts_per_page'=>$posts_per_page,
            'paged'=>$paged,
        );

        if(is_array($tag)){
            if(!$and){
                $args['tag_slug__in'] = $tag;
            }else{
                $args['tag_slug__and'] = $tag;
            }

        }else{
            $args['tag'] = $tag;
        }

        if(count($order) !=0){
            foreach($order as $key => $val){
                $args['orderby'] = $key;
                $args['order'] = $val;
            }
        }
        $posts = query_posts($args);
        $result = array();
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                global $post;
                $result[] = new Post(get_object_vars($post));

                //echo '<li>'.get_the_title().'</li>';
            }
        }

        return $result;
    }


    public function search($search_str){
        $posts = query_posts( "s=$search_str" );
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }
    }


    public function getByDate($year = null, $monthnum = null, $day = null, $posts_per_page = -1, $paged =1, $order = array() ){
        $conditions = array(
            'posts_per_page'=>$posts_per_page,
            'paged'=>$paged,
        );


        if(count($order) != 0){
            foreach($order as $key => $val){
                $conditions['orderby'] = $key;
                $conditions['order'] = $val;
            }
        }
        $conditions['year'] = !is_null($year) ? $year : date('Y');
        $conditions['monthnum'] = !is_null($monthnum) ? $monthnum : date('m');
        $conditions['day'] = !is_null($day) ? $day : date('d');
        print_r($conditions);
        $posts = query_posts($conditions);
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }
    }


    //pattern of date like this; year-month-day (2013-05-25)
    public function getPostBetweenDate($start_date, $end_date){
        global $query_string;
        $query_string = '';
        $query_string .= " AND post_date >= '$start_date' AND post_date <= '$end_date'";

        function filter_where($where = '') {
            global $query_string;
            $where .= $query_string;
            return $where;
        }
        add_filter('posts_where', 'filter_where');
        $posts = query_posts($query_string);
        $query_string = null;
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getPermalink($post_id){
        return get_permalink($post_id);
    }


    public function getByCustomFields( array $custom_fields, $post_type = 'post', $args = array()){
        if(count($custom_fields) != 0){
            $args['post_type'] = $post_type;
            $args['meta_query'] = array();
            foreach($custom_fields as $field => $value){
                //$args['meta_query'][] = array('key'=>$field, 'value'=>$value);
                $args['meta_query']['meta_key'] = $field;
                $args['meta_query']['meta_value'] = $value;
                $args['meta_query']['compare'] = 'LIKE';
            }
        }

        $posts = query_posts($args);
        $query_string = null;
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getPostExcerpt($post_id, $number_character = 100){
        $post = get_post( $post_id );
        $excerpt = ( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
        $excerpt = strip_tags($excerpt);
        $output = substr($excerpt, 0, $number_character);
        return $output;
    }

    public function getPostType($post_id){
        return get_post_type($post_id);
    }

    public function getCommentNumber($post_id){
        return get_comments_number($post_id);
    }


    public function getRecentPost($number= 10, $paged = 1, $conditions = array()){
        $conditions['posts_per_page'] = $number;
        $conditions['paged'] = $paged;
        $posts = new WP_Query($conditions);


        $result = array();
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                global $post;
                $result[] = new Post(get_object_vars($post));

                //echo '<li>'.get_the_title().'</li>';
            }
        }

        return $result;

        //$recent_posts = wp_get_recent_posts($conditions);
        //foreach( $recent_posts as $recent ){
        //    echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
        //}
        /*
        if(is_array($posts)){
            $result = array();
            foreach($posts as $post){
                $result[] = new Post(get_object_vars($post));
            }
            return $result;
        }else{
            return null;
        }

    }

    public function getById($id){
        $post = get_post($id);
        if(is_object($post)){
            return  new Post(get_object_vars($post));
        }else{
            return null;
        }
    }

    public function getNextPost($post_id, $in_same_category = false , $excluded_categories = ''){
        global $post;
        $post= get_post($post_id);
        $next_post = get_next_post( $in_same_category, $excluded_categories );
        if(is_object($next_post)){
            return new Post(get_object_vars($next_post));
        }else{
            return null;
        }
    }

    public function getPreviousPost($post_id, $in_same_category = false, $excluded_categories =''){
        global $post;
        $post= get_post($post_id);
        $post_pre = get_previous_post( $in_same_category, $excluded_categories );
        if(is_object($post_pre)){
            return new Post(get_object_vars($post_pre));
        }else{
            return null;
        }
    }

    public function getNextPostLink($post_id, $in_same_category = false, $excluded_categories =''){
        $next_post = $this->getNextPost($post_id, $in_same_category = false, $excluded_categories ='');
        if(is_object($next_post)){
            return $this->getPermalink($next_post->get('ID'));
        }else{
            return null;
        }
    }

    public function getPreviousPostLink($post_id, $in_same_category = false, $excluded_categories =''){
        $pre_post = $this->getPreviousPost($post_id, $in_same_category = false, $excluded_categories ='');
        if(is_object($pre_post)){
            return $this->getPermalink($pre_post->get('ID'));
        }else{
            return null;
        }
    }

    public function getPostThumbnail($post_id, $size){
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if($thumbnail_id){
            return wp_get_attachment_image_src($thumbnail_id, $size);
        }else{
            return null;
        }
    }

    public function insert($post_array, $return_error = false){
        return wp_insert_post($post_array, $return_error);
    }

    public function update($post_array, $return_error = false){
        return wp_update_post($post_array, $return_error);
    }

    public function delete($post_id, $force= false){
        return wp_delete_post($post_id);
    }

    public function publish($post_id){
        return wp_publish_post($post_id);
    }

    public function moveToTrash($post_id){
        return wp_trash_post($post_id);
    }

    public function doQuery($query_string){
        global $wpdb;
        $rows = $wpdb->get_results($query_string, OBJECT);
        if(is_array($rows)){
            $result = array();
            foreach($rows as $row){
                $result[] = new Post(get_object_vars($row));
            }
            return $result;
        }else{
            return null;
        }
    }

    */
    
}