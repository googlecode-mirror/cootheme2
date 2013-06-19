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
}