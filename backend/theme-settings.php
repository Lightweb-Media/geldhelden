<?php 

/**
 * Register menu "Theme settings"
 */
function register_theme_settings_menu(){

    add_menu_page( 
        __( 'Geldhelden Theme', 'geldhelden' ),
        'Theme Settings',
        'manage_options',
        'geldhelden',
        'geldhelden_theme_settings',
        'dashicons-admin-customizer',
        90
    ); 

    add_action( 'admin_init', 'register_geldhelden_settings' );

}
add_action( 'admin_menu', 'register_theme_settings_menu' );

function register_geldhelden_settings() {
    register_setting( 'geldhelden-settings', 'geldhelden_language' );
}
 
/**
 * Display settings form
 */
function geldhelden_theme_settings(){ 

    $geldhelden_language = esc_attr( get_option('geldhelden_language') );

    ?>
    
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e('Geldhelden Einstellungen', 'geldhelden'); ?></h1>
        <hr class="wp-header-end">
        
        <form action="<?php echo esc_url( admin_url('options.php') ); ?>" method="post">

            <?php settings_fields( 'geldhelden-settings' ); ?>
            <?php do_settings_sections( 'geldhelden-settings' ); ?>

            <table class="form-table">

                <!-- Sprache -->
                <tr valign="top">
                    <th scope="row"><?php _e('Sprache', 'geldhelden'); ?></th>
                    <td>

                        <!-- German -->
                        <label for="geldhelden_language_de" class="fancy-label">
                            <h3><?php _e('Deutsch', 'geldhelden'); ?></h3>
                            <img class="flag" src="<?php echo get_template_directory_uri() . '/img/flags/german.png'; ?>">
                            <input type="radio" name="geldhelden_language" id="geldhelden_language_de" value="de_DE" <?php echo ( isset($geldhelden_language) && $geldhelden_language == 'de_DE' ? 'checked' : ''); ?>>
                        </label>

                        <!-- Englisch -->
                        <label for="geldhelden_language_en" class="fancy-label">
                            <h3><?php _e('Englisch', 'geldhelden'); ?></h3>
                            <img class="flag" src="<?php echo get_template_directory_uri() . '/img/flags/english.png'; ?>">
                            <input type="radio" name="geldhelden_language" id="geldhelden_language_en" value="en" <?php echo ( isset($geldhelden_language) && $geldhelden_language == 'en' ? 'checked' : ''); ?>>
                        </label>

                        <!-- Russisch -->
                        <label for="geldhelden_language_ru" class="fancy-label">
                            <h3><?php _e('Russisch', 'geldhelden'); ?></h3>
                            <img class="flag" src="<?php echo get_template_directory_uri() . '/img/flags/russian.png'; ?>">
                            <input type="radio" name="geldhelden_language" id="geldhelden_language_ru" value="ru_RU" <?php echo ( isset($geldhelden_language) && $geldhelden_language == 'ru_RU' ? 'checked' : ''); ?>>
                        </label>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>
    </div>

    <style>
    label.fancy-label {
        background: #fff;
        display: block;
        text-align: center;
        width: 33.333%;
        float: left;
        box-sizing: border-box;
        padding: 20px;
    }
    label.fancy-label h3 {
        margin-top: 0;
    }
    label img{
        width: 100%;
        margin-bottom: 15px;
    }
    </style>

<?php }