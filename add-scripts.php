<?php
/*
 * Plugin Name:       Scriptify | Scripts in head/footer/body
 * Plugin URI:        https://github.com/vantagdotes/scriptify
 * Description:       Advanced WordPress plugin to add custom scripts or content to head, body, or footer with syntax highlighting and toggle options.
 * Version:           1.1.0
 * Requires at least: 6.3
 * Requires PHP:      7.3
 * Author:            VANTAG.es
 * Author URI:        https://vantag.es
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       scriptify-main
 */

defined('ABSPATH') or die('You shouldnt be here...');

// Registrar opciones al activar el plugin
register_activation_hook(__FILE__, function() {
    add_option('scriptify_settings', [
        'head' => ['code' => '', 'active' => true],
        'body' => ['code' => '', 'active' => true],
        'footer' => ['code' => '', 'active' => true]
    ]);
});

// Añadir menú en el backend
function vantages_add_backend() {
    add_menu_page(
        'Scriptify', 
        'Scriptify', 
        'manage_options', 
        'scriptify', 
        'front_scriptify',
        'dashicons-editor-code',
        80
    );
}
add_action('admin_menu', 'vantages_add_backend');

// Cargar recursos del editor (CodeMirror)
function scriptify_enqueue_assets() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'scriptify') return;

    // CodeMirror desde CDN
    wp_enqueue_script('codemirror', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js', [], '5.65.16', true);
    wp_enqueue_style('codemirror', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css', [], '5.65.16');
    wp_enqueue_script('codemirror-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js', ['codemirror'], '5.65.16', true);
    
    // Estilos y scripts personalizados
    wp_enqueue_style('scriptify-style', plugins_url('assets/style.css', __FILE__), [], '1.1.0');
    wp_enqueue_script('scriptify-script', plugins_url('assets/script.js', __FILE__), ['jquery', 'codemirror'], '1.1.0', true);
}
add_action('admin_enqueue_scripts', 'scriptify_enqueue_assets');

// Interfaz del plugin
function front_scriptify() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    if ($_POST && check_admin_referer('scriptify_save')) {
        $settings = get_option('scriptify_settings', []);
        $sections = ['head', 'body', 'footer'];
        
        foreach ($sections as $section) {
            $settings[$section]['code'] = isset($_POST["script_{$section}_options"]) 
                ? wp_kses_post(stripslashes($_POST["script_{$section}_options"])) 
                : $settings[$section]['code'];
            $settings[$section]['active'] = isset($_POST["script_{$section}_active"]);
        }
        
        update_option('scriptify_settings', $settings);
        echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
    }

    $settings = get_option('scriptify_settings', ['head' => ['code' => '', 'active' => true], 'body' => ['code' => '', 'active' => true], 'footer' => ['code' => '', 'active' => true]]);
    ?>
    <div class="wrap scriptify-wrap">
        <h1>Scriptify</h1>
        <form method="post" action="">
            <?php wp_nonce_field('scriptify_save'); ?>
            <div class="scriptify-tabs">
                <?php foreach (['head' => 'Head', 'body' => 'Body', 'footer' => 'Footer'] as $key => $label): ?>
                    <div class="scriptify-tab">
                        <h2><?php echo esc_html($label); ?></h2>
                        <div class="scriptify-section">
                            <label class="switch">
                                <input type="checkbox" name="script_<?php echo $key; ?>_active" <?php checked($settings[$key]['active']); ?>>
                                <span class="slider"></span>
                            </label>
                            <span>Enable <?php echo $label; ?> Script</span>
                            <textarea class="scriptify-editor" name="script_<?php echo $key; ?>_options" data-section="<?php echo $key; ?>"><?php echo esc_textarea($settings[$key]['code']); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="submit"><input type="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>
    <?php
}

// Inyectar scripts en las ubicaciones correspondientes
function scriptify_inject($section) {
    $settings = get_option('scriptify_settings', []);
    if (!isset($settings[$section]) || !$settings[$section]['active'] || empty(trim($settings[$section]['code']))) return;

    echo "\n<!-- Start of Scriptify {$section} -->\n";
    echo wp_kses_post($settings[$section]['code']);
    echo "\n<!-- End of Scriptify {$section} -->\n";
}

add_action('wp_head', function() { scriptify_inject('head'); });
add_action('wp_body_open', function() { scriptify_inject('body'); });
add_action('wp_footer', function() { scriptify_inject('footer'); });