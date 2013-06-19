<?php
add_action('init', 'create_product_post_type');

function create_product_post_type(){
    $label = array(
                'name' => __('Products'),
                'singular_name'=> __('Products'),
            );
    $args = array(
                'labels'=> $label,
                'public'=> true,
                'menu_position'=>2,
                'has_archive' => true,
                'supports' => array('pr_name','pr_description','thumbnail'),

    );
    register_post_type('product', $args);
}

add_action('init', 'build_brand_taxonomies',false);
function build_brand_taxonomies()
{
    $labels = array(
        'name' => __('Brands'),
        'singular_name' => __('Brands'),
    );
    register_taxonomy( 'brand', 'product',
        array(

            'labels' => $labels,
            'label' => __('Brands'),
            'public' => true,
        ));
}


add_action('admin_menu', 'mytheme_add_box');
// Add meta box
function mytheme_add_box() {
    global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}
$prefix = 'pr_';
$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Custom meta box',
    'page' => 'product',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Name','twentytwelve'),
            'desc' => __('Name of product','twentytwelve'),
            'id' => $prefix . 'name',
            'type' => 'text',
            'std' => 'product name'
        ),
        array(
            'name' => __('Description','twentytwelve'),
            'desc' => __('Enter description','twentytwelve'),
            'id' => $prefix . 'description',
            'type' => 'textarea',
            'std' => 'description'
        ),
    )
);

function mytheme_show_box() {
    global $meta_box, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table">';
    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
        '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
        '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />';
                break;
            case 'textarea':

                //the_editor( $meta ? $meta : $field['std']);
                //echo '<textarea name="', $field['id'], '" id="pr_description_textarea" class="description" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>';

                wp_editor( ($meta ? $meta : $field['std']), $field['id'], array( 'textarea_name' =>  $field['id'] , 'media_buttons' => false));
                break;
        }
        echo     '</td><td>',
        '</td></tr>';
    }
    echo '</table>';
}

add_action('save_post', 'mytheme_save_data');
// Save data from meta box
function mytheme_save_data($post_id) {
    global $meta_box;
    // verify nonce
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}



// ADD NEW COLUMN
function add_columns_head($defaults) {

    $defaults['name'] = __('Name');
    $defaults['description'] = __('Description');
    $defaults['featured_image'] = __('Thumbnail');
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function add_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image'){

        $post_featured_image = get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img width="100px" height="70px"  src="' . $post_featured_image . '" />';

        }
    }
    if ($column_name == 'name') {
        $post_name = get_post_custom_values('pr_name',$post_ID);
        if ($post_name) {
            echo '<label>';
            $post_type = get_post_status($post_ID);
            if($post_type == 'trash') {
                $actionLinks  = '<div class="row-actions"><span class="untrash"><a title="'.__('Restore this item', 'quotable').'" href="'.wp_nonce_url(get_admin_url().'post.php?post='.$post_ID.'&amp;action=untrash', 'untrash-'.$post_type.'_'.$post_ID).'">'.__('Restore', 'quotable').'</a> | </span>';
                $actionLinks .= '<span class="trash"><a href="'.wp_nonce_url(get_admin_url().'post.php?post='.$post_ID.'&amp;action=delete', 'delete-'.$post_type.'_'.$post_ID).'" title="'.__('Delete this item permanently', 'quotable').'" class="submitdelete">'.__('Delete Permanently', 'quotable').'</a></span>';
            }
            else {
                $actionLinks  = '<div class="row-actions"><span class="edit"><a title="'.__('Edit this item', 'quotable').'" href="'.get_admin_url().'post.php?post='.$post_ID.'&amp;action=edit">Edit</a> | </span>';
                $actionLinks .= '<span class="inline hide-if-no-js"><a title="'.__('Edit this item inline', 'quotable').'" class="editinline" href="#">Quick&nbsp;Edit</a> | </span>';
                $actionLinks .= '<span class="trash"><a href="'.wp_nonce_url(get_admin_url().'post.php?post='.$post_ID.'&amp;action=trash', 'delete-post_'.$post_ID).'" title="'.__('Move this item to the Trash', 'quotable').'" class="submitdelete">Trash</a></span>';
            }

            echo $post_name[0].$actionLinks;

            echo '</label>';
        }
    }
    if ($column_name == 'description') {
        $post_name = get_post_custom_values('pr_name',$post_ID);
        $post_description = get_post_custom_values('pr_description',$post_ID);
        if ($post_description) {
            echo '<label>';
            //printf(__('Description %s','vi'),$post_name[0]);
            echo $post_description[0] . '</label>';
            //echo '<pre>';
            //$id= 1;
            //print_r(get_post(1));
            //$post= get_post(1);

            //print_r(Post::getByConditions(array('post_type'=>'product')));
            //echo '</pre>';
        }

    }
}
// GET FEATURED IMAGE
function get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}
add_filter('manage_product_posts_columns', 'add_columns_remove_fields');
add_filter('manage_product_posts_columns', 'add_columns_head',10);
add_action('manage_product_posts_custom_column', 'add_columns_content',10,3);




// REMOVE DEFAULT CATEGORY COLUMN
function add_columns_remove_fields($defaults) {
    // to get defaults column names:
    // print_r($defaults);
    unset($defaults['title']);
    unset($defaults['date']);
    return $defaults;
}

