<?php
/**
 * Plugin Name: Automatic Post Media
 * Plugin URI:  https://wpstore.app/archives/automatic-post-media/
 * Description: Automatic Post Media as a Post
 * Version:     1.0.0
 * Author:      Bestony
 * Author URI:  https://wpstore.app/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: automatic-post-media
 * Domain Path: /languages
 */
function apm_plugin_textdomain() {
    load_plugin_textdomain( 'automatic-post-media', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'apm_plugin_textdomain' );
include( plugin_dir_path( __FILE__ ) . 'options.php');
function apm_custom_upload($file){
    $options = get_option( 'apm_settings' );
    $path_parts = pathinfo($file['file']);
    switch ($options['apm_select_title']) {
      case '1':
        $title = wp_strip_all_tags(sprintf('[%s]%s',$path_parts['extension'],$path_parts['filename']));
        break;
      case '2':
        $title = wp_strip_all_tags($path_parts['basename']);
        break;
      case '3':
        $title = wp_strip_all_tags($path_parts['filename']);
        break;
      default:
        $title = $path_parts['basename'];
        break;
    }
    if (!$options['apm_textarea_content']){
      $options['apm_select_author'] = "<a href='[url]'>[name]</a>";
    }
    $content = sprintf(str_replace('[url]','%s',$options['apm_textarea_content']),$file['url']);
    $content = sprintf(str_replace('[name]','%s',$content),$path_parts['filename']);
    $my_post = array(
      'post_title'    => $title,
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => $options['apm_select_author']?$options['apm_select_author']:1,
      'post_category' => array($options['apm_select_category']?$options['apm_select_category']:1)
    );
    wp_insert_post( $my_post );
}
add_filter('wp_handle_upload','apm_custom_upload');
