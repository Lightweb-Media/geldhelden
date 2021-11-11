<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Add input field for profile img
 */

function add_profile_img_to_backend( $user ) { ?>
    <h2><?php _e('Profilbild', 'geldhelden'); ?></h2>

    <table class="form-table">
        <tr>
            <th><label for="address"><?php _e('Profilbild', 'geldhelden'); ?></label></th>
            <td>
                <input type="url" name="profile_img" id="profile_img" placeholder="https://" value="<?php echo esc_attr( get_the_author_meta( 'profile_img', $user->ID ) ); ?>" class="regular-text code" /><br />
                <span class="description"><?php _e('Füge einen Link zu deinem Profilbild hinzu.', 'geldhelden'); ?></span>
            </td>
        </tr>
    </table>
<?php }

add_action( 'show_user_profile', 'add_profile_img_to_backend' );
add_action( 'edit_user_profile', 'add_profile_img_to_backend' );

/**
 * Save input field for profile img
 */

function save_profile_img_to_backend( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'profile_img', esc_url_raw( $_POST['profile_img'] ) );
}

add_action( 'personal_options_update', 'save_profile_img_to_backend' );
add_action( 'edit_user_profile_update', 'save_profile_img_to_backend' );