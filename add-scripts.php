<?php
/*
 * Plugin Name:       Scriptify | Scripts in head/footer/body
 * Plugin URI:        https://github.com/josejtax/scriptify
 * Description:       Add global code to your website with Scriptify. Analytics code, new styles, user alerts, and much more.
 * Version:           1.0
 * Requires at least: 6.3
 * Requires PHP:      7.3
 * Author:            Jose Manuel 'jtax'
 * Author URI:        https://jtax.dev
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       scriptify-main
 */

defined('ABSPATH') or die('You shouldnt be here...');

function vantages_add_backend(){
    add_menu_page('Scriptify', 'Scriptify', 'manage_options', 'scriptify', 'front_scriptify');
}

add_action('admin_menu', 'vantages_add_backend');

function front_scriptify() {
    echo "<h1>Scriptify</h1>";
    echo "<hr style='border: 1px solid #000'>";
    if ($_POST && check_admin_referer('scriptify_nonce_action', 'scriptify_nonce_field')) {
        update_option('script_head', sanitize_text_field($_POST["script_head_options"]), '', 'yes');
        update_option('script_body', sanitize_text_field($_POST["script_body_options"]), '', 'yes');
        update_option('script_footer', sanitize_text_field($_POST["script_footer_options"]), '', 'yes');
    }
    ?>
    <form action="#" method="post">
        <?php wp_nonce_field('scriptify_nonce_action', 'scriptify_nonce_field'); ?>
        <h3>Head</h3>
        <textarea style='width: 50%; height: 300px;' name="script_head_options"><?php echo esc_textarea(get_option('script_head', '')); ?></textarea>
        <hr>
        <h3>Body</h3>
        <textarea style='width: 50%; height: 300px;' name="script_body_options"><?php echo esc_textarea(get_option('script_body', '')); ?></textarea>
        <hr><br>
        <h3>Footer</h3>
        <textarea style='width: 50%; height: 300px;' name="script_footer_options"><?php echo esc_textarea(get_option('script_footer', '')); ?></textarea>
        <br>
        <input type="submit" value="SAVE">
    </form>
    <?php    
}

add_action('wp_head', 'add_scriptify_head');
add_action('wp_body_open', 'add_scriptify_body');
add_action('wp_footer', 'add_scriptify_footer');

function add_scriptify_head() {
    echo "\n <!--Start of Scriptify head--> \n";
    echo stripslashes(get_option('script_head', ''));
    echo "\n <!--End of Scriptify head--> \n";
}

function add_scriptify_body() {
    echo "\n <!--Start of Scriptify body--> \n";
    echo stripslashes(get_option('script_body', ''));
    echo "\n <!--End of Scriptify body--> \n";
}

function add_scriptify_footer() {
    echo "\n <!--Start of Scriptify footer--> \n";
    echo stripslashes(get_option('script_footer', ''));
    echo "\n <!--End of Scriptify footer--> \n";
}

function scriptify_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'scriptify-block',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'block.js')
    );
}
add_action('enqueue_block_editor_assets', 'scriptify_enqueue_block_editor_assets');

function scriptify_enqueue_scripts() {
    wp_enqueue_script('scriptify-custom-script', plugins_url('/js/custom-script.js', __FILE__), array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'scriptify_enqueue_scripts');