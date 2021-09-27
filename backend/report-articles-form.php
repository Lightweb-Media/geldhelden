<?php 

/**
 * Register menu "Beitrags-Report"
 */
function register_beitrags_report(){
    add_menu_page( 
        __( 'Beitrag-Report', 'geldhelden' ),
        'Beitrags-Report',
        'manage_options',
        'beitrags-report',
        'beitrags_report_form',
        'dashicons-chart-pie',
        6
    ); 
}
add_action( 'admin_menu', 'register_beitrags_report' );
 
/**
 * Display form
 */
function beitrags_report_form(){ 
    ?>
    
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e('Beitrags-Report senden', 'geldhelden'); ?></h1>
        <hr class="wp-header-end">

        <?php
        if( isset($_GET['nor']) ){ 
            if( $_GET['nor'] > 0 ){ ?>
                <div class="updated notice">
                    <p><?php echo wp_sprintf( __( 'Anfrage erfolgreich versendet. Sie haben im gewählten Zeitraum %d Beiträge mit über 1.000 Wörtern veröffentlicht.', 'geldhelden' ), $_GET['nor'] ); ?></p>
                </div>
            <?php }else{ ?>
                <div class="error notice">
                    <p><?php _e('Sie haben im gewählten Zeitraum keine Beiträge mit über 1.000 Wörtern veröffentlicht.', 'geldhelden'); ?></p>
                </div>
            <?php } ?>
        <?php } ?>
        
        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

            <?php wp_nonce_field( 'HDwieue92djosad0', 'validation' ); ?>
            <input type='hidden' name='action' value='send_beitrags_report'>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="wallet-id"><?php _e('Wallet-ID', 'geldhelden'); ?></label></th>
                        <td><input name="wallet-id" type="text" id="wallet-id" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="user-name"><?php _e('Name', 'geldhelden'); ?></label></th>
                        <td><input name="user-name" type="text" id="user-name" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email"><?php _e('E-Mail', 'geldhelden'); ?></label></th>
                        <td><input name="email" type="email" id="email" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="month"><?php _e('Monat', 'geldhelden'); ?></label></th>
                        <td>
                            <?php
                            
                            $months = array(
                                'Januar',
                                'Februar',
                                'März',
                                'April',
                                'Mai',
                                'Juni',
                                'Juli',
                                'August',
                                'September',
                                'Oktober',
                                'November',
                                'Dezember'
                            );

                            ?>

                            <select name="month" id="month" required>
                                <option value="" disabled selected><?php _e('Bitte auswählen...', 'geldhelden'); ?></option>
                                <?php
                                $i = 1;
                                foreach($months as $month){ ?>

                                    <option value="<?php echo $i; ?>"><?php echo $month; ?></option>

                                <?php 
                                $i++;

                                } ?>                               
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="year"><?php _e('Jahr', 'geldhelden'); ?></label></th>
                        <td>
                            <?php $current_year = date('Y'); ?>

                            <select name="year" id="year" required>
                                <?php
                                foreach( range(2021, $current_year) as $year ){ ?>
                                    <option value="<?php echo $year; ?>" <?php echo ( $current_year == $year ? 'selected' : ''); ?>><?php echo $year; ?></option>
                                <?php } ?>                       
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="posts_amount"><?php _e('Anzahl Beiträge', 'geldhelden'); ?></label></th>
                        <td><input type="number" name="posts_amount" id="posts_amount" class="regular-text" required></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Absenden', 'geldhelden') ?>"></p>
        </form>
    </div>

<?php }

/**
 * Add style to backend
 */

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() { ?>

    <style>
        
    </style>

<?php }

/**
 * Form Submission Handler
 */

function beitrags_report_form_submission_handler(){

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if ( !isset( $_POST['validation'] ) || !wp_verify_nonce( $_POST['validation'], 'HDwieue92djosad0' ) ) {
 
            print __('Leider ist ein Fehler aufgetreten! Bitte versuchen Sie es erneut!', 'geldhelden');
            exit;
 
        }elseif( $_POST['submit'] ){
        
            global $wpdb;

            $wallet_id = sanitize_text_field($_POST['wallet-id']);
            $user_name = sanitize_text_field($_POST['user-name']);
            $email = sanitize_email($_POST['email']);
            $month = sanitize_text_field($_POST['month']);
            $year = sanitize_text_field($_POST['year']);
            $posts_amount = sanitize_text_field($_POST['posts_amount']);

            // Get all posts by current user id
            $query = array(
                'posts_per_page' => '-1', 
                'post_status' => 'publish', 
                'author' => get_current_user_id(),
                'date_query' => array(
                    'year' => $year,
                    'month' => $month
                )
            );
            $author_posts = new WP_Query($query);

            // Get number of articles over 1000 words
            $articles_over_thousand = 0;
            while($author_posts->have_posts()) : $author_posts->the_post();
                $number_of_words = wp_count_words();
                if( $number_of_words >= 1000){
                    $articles_over_thousand++;        
                }
            endwhile;

            // Send only mail, if more than one article > 1000 words 
            if( $articles_over_thousand > 0 ){

                $to = 'hey@moneyhero.io';
                $subject = wp_sprintf( __('Auszahlung beantragt: %s', 'geldhelden'), $user_name );
                $body = wp_sprintf( __( 'Neue Auszahlung beantragt für %d. %d', 'geldhelden' ), (($month < 10) ? '0' . $month : $month), $year ) . "<br><br>";
                $body .= wp_sprintf( __( 'Wallet-ID: %s', 'geldhelden' ), $wallet_id ) . "<br>";
                $body .= wp_sprintf( __( 'Name: %s', 'geldhelden' ), $user_name ) . "<br>";
                $body .= wp_sprintf( __( 'E-Mail: %s', 'geldhelden' ), $email ) . "<br>";
                $body .= wp_sprintf( __( 'Artikel gesamt: %d', 'geldhelden' ), $posts_amount ) . "<br>";
                $body .= wp_sprintf( __( '- davon Artikel über 1000 Wörter: %d', 'geldhelden' ), $articles_over_thousand ) . "<br>";
                $body .= wp_sprintf( __( 'Blog: %s - %s', 'geldhelden' ), get_bloginfo( 'name' ), get_site_url() );

                $headers = array('Content-Type: text/html; charset=UTF-8');
                
                wp_mail( $to, $subject, $body, $headers );

            }

            // Redirect user
            $arr_params = array( 
                'page' => 'beitrags-report',
                'nor' => $articles_over_thousand
            );

			$url = add_query_arg( $arr_params, admin_url( '/admin.php' ) );
            wp_redirect( $url );
            exit;
            
        } 
    }
}
add_action( 'admin_post_send_beitrags_report', 'beitrags_report_form_submission_handler' );

/**
 * Function Count Words
 */

function wp_count_words(){
    ob_start();
    the_content();
    $content = ob_get_clean();
    return sizeof(explode(" ", $content));
}

/**
 * Filter: wp_mail change from name
 */

function sender_name( $original_email_from ) {
    return 'Geldhelden';
}
add_filter( 'wp_mail_from_name', 'sender_name' );
