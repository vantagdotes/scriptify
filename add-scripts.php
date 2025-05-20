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
 * Text Domain:       scriptify
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
	if (!is_admin()) {
		return;
	}

	if (isset($_POST['scriptify_nonce'])) {
		$nonce = filter_input(INPUT_POST, 'scriptify_nonce', FILTER_SANITIZE_STRING);
		if (empty($nonce) || !wp_verify_nonce($nonce, 'scriptify_save')) {
			return;
		}

		if (isset($_POST['script_head_options'])) {
			$head_content = sanitize_textarea_field(wp_unslash($_POST['script_head_options']));
			update_option('script_head', $head_content, '', 'yes');
		}

		if (isset($_POST['script_body_options'])) {
			$body_content = sanitize_textarea_field(wp_unslash($_POST['script_body_options']));
			update_option('script_body', $body_content, '', 'yes');
		}

		if (isset($_POST['script_footer_options'])) {
			$footer_content = sanitize_textarea_field(wp_unslash($_POST['script_footer_options']));
			update_option('script_footer', $footer_content, '', 'yes');
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e('Scriptify', 'scriptify'); ?></h1>
		<hr style="border: 1px solid #000">
		
		<form action="" method="post">
			<?php wp_nonce_field('scriptify_save', 'scriptify_nonce'); ?>
			
			<h3><?php esc_html_e('Head', 'scriptify'); ?></h3>
			<textarea style='width: 50%; height: 300px;' name="script_head_options"><?php echo esc_textarea(get_option('script_head', '')); ?></textarea>
			<hr>

			<h3><?php esc_html_e('Body', 'scriptify'); ?></h3>
			<textarea style='width: 50%; height: 300px;' name="script_body_options"><?php echo esc_textarea(get_option('script_body', '')); ?></textarea>
			<hr>

			<h3><?php esc_html_e('Footer', 'scriptify'); ?></h3>
			<textarea style='width: 50%; height: 300px;' name="script_footer_options"><?php echo esc_textarea(get_option('script_footer', '')); ?></textarea>
			<hr>

			<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'scriptify'); ?>">
		</form>
	</div>
	<?php
}

/**
 * 
 * HEAD
 * 
 **/

function add_scriptify_head() {
	echo "\n <!--Start of Scriptify head--> \n";
			echo wp_kses_post(get_option('script_head', ''));
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
		echo wp_kses_post(get_option('script_body', ''));
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
		echo wp_kses_post(get_option('script_footer', ''));
	echo "\n <!--End of Scriptify footer--> \n";
}
add_action('wp_footer', 'add_scriptify_footer');