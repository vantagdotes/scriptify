<?php
/*
 * Plugin Name:       Scriptify | Scripts & content in head/footer/body
 * Plugin URI:        https://github.com/vantagdotes/scriptify
 * Description:       This is a simple WordPress plugin that allows you to add content or scripts to the head, body, or footer of your website quickly and easily. This is useful for inserting scripts from services like Google Analytics or other custom scripts on your WordPress website.
 * Version:           1.0
 * Requires at least: 6.3
 * Requires PHP:      8.0
 * Author:            VANTAG.es
 * Author URI:        https://vantag.es
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       scriptify-main
 */

defined('ABSPATH') or die('You shouldnt be here...');

/**
 * 
 * AÑADE MENU EN EL ADMINISTRADOR DE WORDPRESS
 * 
 **/

function vantages_add_backend(){
    add_menu_page( 'Scriptify', 'Scriptify', 'manage_options', 'scriptify', 'front_scriptify' );
}

add_action('admin_menu', 'vantages_add_backend');

/**
 * 
 * FRONTEND DEL PLUGIN
 * 
 **/

function front_scriptify() {
	echo "<h1>Scriptify</h1>";
	echo "<hr style='border: 1px solid #000'>";
	 if ($_POST) {
		update_option( 'script_head', $_POST["script_head_options"], '', 'yes' );
		update_option( 'script_body', $_POST["script_body_options"], '', 'yes' );
		update_option( 'script_footer', $_POST["script_footer_options"], '', 'yes' );
	 }

	 ?>
		<form action="#" method="post">
			<h3>Head</h3>
			<textarea style='width: 50%; height: 300px;' name="script_head_options"><?= stripslashes(get_option('script_head', '')) ?></textarea>
			<hr>

            <h3>Body</h3>
			<textarea style='width: 50%; height: 300px;' name="script_body_options"><?= stripslashes(get_option('script_body', '')) ?></textarea>
            <hr><br>

            <h3>Footer</h3>
			<textarea style='width: 50%; height: 300px;' name="script_footer_options"><?= stripslashes(get_option('script_footer', '')) ?></textarea>
            <br>
			<input type="submit" value="SAVE">
		</form>
	<?php
}

/**
 * 
 * HEAD
 * 
 **/

function add_scriptify_head() {
	echo "\n <!--Start of Scriptify head--> \n";
    		echo stripslashes(get_option('script_head', ''));
	echo "\n <!--End of Scriptify head--> \n";
}
add_action('wp_head', 'add_scriptify_head');

/**
 * 
 * BODY
 * 
 **/

function add_scriptify_body() {
	echo "\n <!--Start of Scriptify body--> \n";
		echo stripslashes(get_option('script_body', ''));
	echo "\n <!--End of Scriptify body--> \n";
}
add_action('wp_body_open', 'add_scriptify_body');

/**
 * 
 * FOOTER
 * 
 **/

function add_scriptify_footer() {
	echo "\n <!--Start of Scriptify footer--> \n";
		echo stripslashes(get_option('script_footer', ''));
	echo "\n <!--End of Scriptify footer--> \n";
}
add_action('wp_footer', 'add_scriptify_footer');