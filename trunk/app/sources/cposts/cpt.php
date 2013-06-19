<?php
add_action('init', 'create_cpt_post_type');

function create_cpt_post_type(){
    $label = array(
                'name' => __('CPT'),
                'singular_name'=> __('CPT'),
            );
    $args = array(
                'labels'=> $label,
                'public'=> true,
                'menu_position'=>2,
                'has_archive' => true,
                'supports' => array('pr_name','pr_description','thumbnail'),

    );
    register_post_type('cpt', $args);
}
