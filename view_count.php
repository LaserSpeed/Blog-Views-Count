<?php

/**
 * Plugin Name:       BLog view Count
 * Description:       Keep track how many times your blogs are viewed
 * Version:           1.0.0
 * Author:            Nitish Rajbongshi
 * License:           GPL v2 or later
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

// restrict unwanted access
if (!defined('ABSPATH')) {
    header('Location: /');
    exit();
}

// main task
// count how many times the blogs are viewed
function count_blog_view() {
    // check whether it is a single post, page or attachment
    if(is_single()) {
        // global variable 
        global $post;

        // get the post meta field for the given post id
        $views = get_post_meta($post->ID, 'view', true);

        if($views == '') {
            // add a new metavalue
            // 1 is inital value
            add_post_meta($post->ID, 'view', 1);
        }
        else {
            $views++; // increment the views whenever the blog is viewed

            // update the meta
            update_post_meta($post->ID, 'view', $views);
        }
    }
}

function custom_style() {
    $path = plugins_url('css/main.css', __FILE__);
    $ver_style = filemtime(plugin_dir_path(__FILE__, 'css/main.css'));
    wp_enqueue_style('my_custom_style', $path, '', $ver_style);
}

add_action('wp_enqueue_scripts', 'custom_style');

add_action('wp_head', 'count_blog_view'); // hook to call the function

// adding a shortcode to view the total views of the blog
function blog_view_total() {
    global $post;
    ob_start();
    ?>
    <div class="view_page_style">
        <p><span class="view_blog">Views: </span><span class="view_number"><?php echo  get_post_meta($post->ID, 'view', 1);?></span></p>
    </div>
    <?php 
    $html = ob_get_clean();

    return $html;
}

add_shortcode('total_blog_views', 'blog_view_total');