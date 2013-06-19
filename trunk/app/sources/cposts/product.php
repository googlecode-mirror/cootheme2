<?php
/**
 * Template Name: Product
 * Created by JetBrains PhpStorm.
 * User: khang
 * Date: 5/9/13
 * Time: 8:32 AM
 * To change this template use File | Settings | File Templates.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

    <div id="primary" class="site-content">
        <div id="content" role="main">
            <?php
            //Khai báo tên post type sẽ được hiển thị và số bài hiển thị mỗi trang
            $args = array('post_type' => 'product','posts_per_page' => 10);
            $loop = new WP_Query ($args);
            while ( $loop->have_posts() ): $loop->the_post();

                ?>
                <header class="entry-header">
                    <h1 class="entry-title"><a href="<?php the_permalink();?>" rel="bookmark" class="entry-title"><?php $product_name= get_post_custom_values('pr_name',$post->ID); echo $product_name[0];?></a></h1>
                </header>
                <?php
                echo '<div class="entry-content">';
                the_content();
                echo '</div>';

            endwhile;
            ?>
        </div><!-- #content -->
    </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
