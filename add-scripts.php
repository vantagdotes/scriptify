<?php
/**
* Plugin Name: Scriptify | Scripts in head/footer/body
* Plugin URI: https://github.com/jt4x/scriptify
* Description: Add global code to your website with Scriptify. Analytics code, new styles, user alerts, and much more.
* Version: 1
* Author: Jose Manuel 'jtax'
* Author URI: https://jtax.dev
**/

defined('ABSPATH') or die('You shouldnt be here...');

/*Añade al menu del backend la opcion del plugin*/
function vantages_add_backend(){
    add_menu_page( 'Scriptify', 'Scriptify', 'manage_options', 'scriptify', 'front_scriptify' );
}

add_action('admin_menu', 'vantages_add_backend');

/*Muestra en el backend las opciones disponibles del plugin*/
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

/* Añade el script en el head */
function add_scriptify_head() {
	echo "\n <!--Start of Scriptify head--> \n";
    		echo stripslashes(get_option('script_head', ''));
	echo "\n <!--End of Scriptify head--> \n";
}
add_action('wp_head', 'add_scriptify_head');

function add_scriptify_body() {
	echo "\n <!--Start of Scriptify body--> \n";
		echo stripslashes(get_option('script_body', ''));
	echo "\n <!--End of Scriptify body--> \n";
}
add_action('wp_body_open', 'add_scriptify_body');

/* Añade el script en el footer */
function add_scriptify_footer() {
	echo "\n <!--Start of Scriptify footer--> \n";
		echo stripslashes(get_option('script_footer', ''));
	echo "\n <!--End of Scriptify footer--> \n";
}
add_action('wp_footer', 'add_scriptify_footer');
