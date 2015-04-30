<?php
/*
Plugin Name: WP Magic Carousel
Plugin URI: http://hf-it.org/plugins
Author: HelpFul IT
Description: This is Wp magic carousel plugin. When you activate it in your WordPress site it will make a slider area with header & description text.

Author URI: http://hf-it.org/
Version: 1.0
*/
if (!function_exists('a_carousel_language_supported')) {
function a_carousel_language_supported() {  
        load_theme_textdomain ('acarousel' , get_template_directory().'/languages');
}
add_action( 'after_setup_theme', 'a_carousel_language_supported' );
}

/* Adding Latest jQuery from Wordpress */
if (!function_exists('acarousel_latest_jquery')) {
function acarousel_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'acarousel_latest_jquery');
}
/*Default Supports*/

add_theme_support('post-thumbnails');
add_image_size('caro_img', 1024, 768,true);


/*file link*/
define('ACAROUSEL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/* Adding plugin js/css file */
function acarousel_css_and_js() {
    wp_enqueue_script('a-tiksluscarousel_js', ACAROUSEL.'js/tiksluscarousel.js', array('jquery'));
    wp_enqueue_script('a-rainbow', ACAROUSEL.'js/rainbow.min.js', array('jquery'));
    wp_enqueue_script('a-active', ACAROUSEL.'js/active.js', array('jquery'));
    wp_enqueue_style('a-normalize', ACAROUSEL.'css/normalize.css'); 
    wp_enqueue_style('a-tiksluscarousel', ACAROUSEL.'css/tiksluscarousel.css'); 
    wp_enqueue_style('a-github', ACAROUSEL.'css/github.css'); 
    wp_enqueue_style('a-animate', ACAROUSEL.'css/animate.min.css');    
    wp_enqueue_style('a-custom',ACAROUSEL.'css/custom.css',array(),'1.0','all');
}
add_action('wp_enqueue_scripts', 'acarousel_css_and_js');


/*Add custom post*/
if (!function_exists('a_carousel_custom_posts')) {
function a_carousel_custom_posts(){
    register_post_type('a_carousel',array(
        'public'             => true,
        'label'              => __('Carousels','acarousel'),
        'menu_position'          => 2,
        'menu_icon'          => 'dashicons-images-alt',
        'has_archive'        => true,
        'labels'  => array(
            'name'              => __('Magic-Carousels','acarousel'),
            'singular_name'     => __('Carousel','acarousel'),
            'add_new_item'      => __('Add New Carousel','acarousel'),
            'edit_item'         => __('Edit Carousel','acarousel'),
            'new_item'          => __('New Carousel','acarousel'),
            'view_item'         => __('Carousel','acarousel'),
            'not_found'         => __('Sorry, we couldnt find the Carousels you are looking for.','acarousel')

        ),
      'supports' => array('title','thumbnail','page-attributes')
    ));
}
add_action('init','a_carousel_custom_posts'); 
}

/*Catogery Supports*/
if (!function_exists('custom_post_taxonomy')) {
function custom_post_taxonomy(){			
    register_taxonomy(
        'carousel_cat',
        'a_carousel',		 //post type name
        array(
            'hierarchical' => true,
            'lebel'	 => __('Categories','acarousel'),    //display name
            'query_var' => true,
            'show_admin_column' => true,
            'rewrite' => array(
                'slug' => __('carousel-category','acarousel'),    // This controls the base slug that will display before each term.
                'with_front' => true,  //don't display the catogory base before
            ))
    );
}
add_action('init','custom_post_taxonomy');
}

if (!function_exists('a_carousel_insert_shortcode')) {
function a_carousel_insert_shortcode($atts, $content = null){
		extract( shortcode_atts( array(
		'title' => '',
		'text' => '',
		'cat' => '',
		'cstyl' => 'thumbnails',
        'width' => '640',
		'bcolor' => '#000',
		'bsize' => '10',
		'bstyle' => 'solid',
		'bradius' => '',
		'ftext' => '',
		
		), $atts) );
        
        $q = new WP_Query(
            array(
                'post_type' => 'a_carousel', 'posts_per_page' => -1, 'orderby' => 'menu_order','order' => 'ASC', 'carousel_cat'=>$cat,
            ));		
        $list = '
        <div class="acar-header">
            <acspan class="acar-title">'.$title.'</acspan>
            <p class="text-center">'.$text.'</p>
        </div>
        <div id="hf_'.$cstyl.'" style="
            border:'.$bsize.'px '.$bstyle.' '.$bcolor.';
            border-radius:'.$bradius.'px; 
            max-width: '.$width.'px;
        "><ul>        
        ';

        while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        $caroimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID),'caro_img'); 

        $list .= '      
        <li><img src="'.$caroimg[0].'" alt="This is carousel images"></li>
        ';        
        endwhile;
        $list.= '</ul></div>
        <h2 class="headings">'.$ftext.'</h2>
        ';
        wp_reset_query();
        return $list;
	} 	
	add_shortcode('magic_carousel','a_carousel_insert_shortcode');
}





// Hooks your functions into the correct filters
if (!function_exists('my_add_mce_button')) {
function my_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'my_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'my_register_mce_button' );
	}
}
add_action('admin_head', 'my_add_mce_button');
}
// Declare script for new button
if (!function_exists('my_add_tinymce_plugin')) {
function my_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['my_mce_button'] = plugins_url('js/mce-button.js', __FILE__);
	return $plugin_array;
}
}

// Register new button in the editor
if (!function_exists('my_register_mce_button')) {
function my_register_mce_button( $buttons ) {
	array_push( $buttons, 'my_mce_button' );
	return $buttons;
}}

if (!function_exists('a_carousel_buttons_files')) {
function a_carousel_buttons_files(){
		wp_register_style('a_car_mce_icons', ACAROUSEL.'css/mce-icons.css');
		wp_enqueue_style('a_car_mce_icons');
	}
	add_action('admin_enqueue_scripts','a_carousel_buttons_files');
}

