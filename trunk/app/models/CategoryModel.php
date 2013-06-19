<?php
/**
 * Created by JetBrains PhpStorm.
 * User: khang
 * Date: 5/23/13
 * Time: 2:28 PM
 * To change this template use File | Settings | File Templates.
 */


class CategoryModel extends Coo_Mvc_Model_BaseModel {

    public function __construct(){
        parent::__construct();
    }

    public function get($args){
        $categories = get_categories($args);
        if(is_array($categories)){
            $result = array();
            foreach($categories as $category){
                $result[] = $this->createCategoryObject($category);
            }
            return $this->buildCategoryTree($result);
        }else{
            return null;
        }
    }

    public function getAll(){
        $categories = $this->get('');
        if(count($categories)!= 0){
            return $this->buildCategoryTree($categories);
        }else{
            return $categories;
        }
    }

    public function getById($id){
        $category = get_category($id);
        if(is_object($category)){
            return $this->createCategoryObject($category);
        }else{
            return null;
        }
    }

    

    public function getChildrens($id = '', $args = array()){
        if(is_array($args)){
            $args['child_of'] = $id;
        }else{
            $args = array('child_of'=>$id);
        }
        $children = get_categories($args);
        if(is_array($children)){
            $result = array();
            foreach($children as $child){
                $result[] = $this->createCategoryObject($child);
            }
            if(count($result)!= 0){
                return $this->buildCategoryTree($result,$id);
            }
        }else{
            return null;
        }
    }


    public function buildCategoryTree(array $elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parent == $parentId) {
                $children = $this->buildCategoryTree($elements, $element->id);
                if ($children) {
                    $element->childrens = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    private function createCategoryObject($cate){
        $params = array(
            'name' => get_cat_name($cate->cat_ID),
            'link'=> get_category_link($cate),
        );

        return new Category($params + get_object_vars($cate));

    }



    /*

    public function getById($id){
        $category = get_category($id);
        if(is_object($category)){
            return new Category(get_object_vars($category));
        }else{
            return null;
        }
    }

    public function getChildren($id = '', $args = array()){
        if(is_array($args)){
            $args['child_of'] = $id;
        }else{
            $args = array('child_of'=>$id);
        }
        $children = get_categories($args);
        if(is_array($children)){
            $result = array();
            foreach($children as $child){
                $result[] = new Category(get_object_vars($child));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getByConditions($conditions = ''){
        $categories = get_categories($conditions);
        if(is_array($categories)){
            $result = array();
            foreach($categories as $category){
                $result[] = new Category(get_object_vars($category));
            }
            return $result;
        }else{
            return null;
        }
    }

    public function getCategoryTree($root = 0, $args = array()){
        if(is_array($args)){
            $args['child_of'] = $root;
        }else{
            $args = array('child_of'=>$root);
        }
        $children = get_categories($args);
        if(is_array($children)){
            $categories = array();
            foreach($children as $child){
                $categories[] = get_object_vars($child);
            }
            return $this->buildCategoryTree($categories, $root);
        }else{
            return null;
        }

    }


    public function buildCategoryTree(array $elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['category_parent'] == $parentId) {
                $children = $this->buildCategoryTree($elements, $element['cat_ID']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function doQuery($query_string){
        global $wpdb;
        $rows = $wpdb->get_results($query_string, OBJECT);
        if(is_array($rows)){
            $result = array();
            foreach($rows as $row){
                $result[] = new Category(get_object_vars($row));
            }
            return $result;
        }else{
            return null;
        }
    }
    */
}