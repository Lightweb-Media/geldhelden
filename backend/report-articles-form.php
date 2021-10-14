<?php 

/**
 * Create database table
 */

function create_report_articles_db_table(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_reported_articles'; 

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        ura_id mediumint(9) NOT NULL AUTO_INCREMENT,
        date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        user_id mediumint(9) NOT NULL,
        month mediumint(9) NOT NULL,
        year mediumint(9) NOT NULL,
        posts_amount mediumint(9) NOT NULL,
        PRIMARY KEY (ura_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}

add_action('after_switch_theme', 'create_report_articles_db_table');

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

    $user_id = get_current_user_id();

    $ra_wallet_id = get_user_meta( $user_id, 'ra_wallet_id', true );
    $ra_user_name = get_user_meta( $user_id, 'ra_user_name', true );
    $ra_email = get_user_meta( $user_id, 'ra_email', true );
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
        <?php }elseif( isset($_GET['err']) ){ 

            $error_msg = __('Der Beitrags-Report wurde nicht abgeschickt, da dieser für den gewählten Zeitraum bereits versendet wurde!', 'geldhelden');

            ?>
            <div class="error notice">
                <p><?php echo $error_msg; ?></p>
            </div>
        <?php } ?>

        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

            <?php wp_nonce_field( 'HDwieue92djosad0', 'validation' ); ?>
            <input type='hidden' name='action' value='send_beitrags_report'>
            <input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="wallet-id"><?php _e('Wallet-ID', 'geldhelden'); ?></label></th>
                        <td><input name="wallet-id" type="text" id="wallet-id" class="regular-text" value="<?php echo ( isset($ra_wallet_id) && !empty($ra_wallet_id) ? esc_attr($ra_wallet_id) : '' ); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="user-name"><?php _e('Name', 'geldhelden'); ?></label></th>
                        <td><input name="user-name" type="text" id="user-name" class="regular-text" value="<?php echo ( isset($ra_user_name) && !empty($ra_user_name) ? esc_attr($ra_user_name) : '' ); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email"><?php _e('E-Mail', 'geldhelden'); ?></label></th>
                        <td><input name="email" type="email" id="email" class="regular-text" value="<?php echo ( isset($ra_email) && !empty($ra_email) ? sanitize_email($ra_email) : '' ); ?>" required></td>
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


        <h2 class="title"><?php _e('Beiträge der letzten 45 Tage', 'geldhelden'); ?></h2>

        <?php 
        // Get all posts by current user id
        $query = array(
            'posts_per_page' => '-1', 
            'post_status' => 'publish', 
            'author' => $user_id,
            'date_query' => array(
                array(
                    'after' => '-45 days',
                    'column' => 'post_date',
                )
            )
        );
        $author_posts = new WP_Query($query);

        // Show table with all posts for current user
        if( !empty($author_posts) ){
        ?>

            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <thead>
                    <tr>
                        <th scope="col" id="title" class="manage-column column-title"><?php _e('Titel', 'geldhelden'); ?></th>
                        <th scope="col" id="date" class="manage-column column-date"><?php _e('Datum', 'geldhelden'); ?></th>
                        <th scope="col" id="date" class="manage-column column-date"><?php _e('Wörter', 'geldhelden'); ?></th>	
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php
                    while($author_posts->have_posts()) : $author_posts->the_post();
                        $post_date = get_the_date();
                        $post_title = get_the_title();
                        $number_of_words = number_format( wp_count_words(), 0, '', '.');
                    ?>
                    <tr>
                        <td class="title column-title page-title" data-colname="Titel">
                            <strong><a class="row-title" href="<?php echo get_the_permalink(); ?>"><?php echo esc_attr( $post_title ); ?></a></strong>
                        </td>
                        <td class="date column-date" data-colname="Datum"><?php _e('Veröffentlicht', 'geldhelden'); ?><br><?php echo esc_attr( $post_date ); ?></td>	
                        <td class="date column-words" data-colname="Wörter"><?php echo wp_sprintf( '%s ' . __( 'Wörter', 'geldhelden' ), esc_attr( $number_of_words ) ); ?></td>		
                    </tr>
                    <?php endwhile; ?>
                </tbody>       
            </table>

        <?php } ?>

    </div>

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

            $user_id = sanitize_text_field($_POST['user_id']);
            $wallet_id = sanitize_text_field($_POST['wallet-id']);
            $user_name = sanitize_text_field($_POST['user-name']);
            $email = sanitize_email($_POST['email']);
            $month = sanitize_text_field($_POST['month']);
            $year = sanitize_text_field($_POST['year']);
            $posts_amount = sanitize_text_field($_POST['posts_amount']);

            // Update user details
            update_user_meta( $user_id, 'ra_wallet_id', $wallet_id );
            update_user_meta( $user_id, 'ra_user_name', $user_name );
            update_user_meta( $user_id, 'ra_email', $email );

            // Check if user has already sent his report for this month
            $get_reports = $wpdb->get_results( $wpdb->prepare( "
                SELECT * 
                FROM {$wpdb->prefix}user_reported_articles
                WHERE user_id = %d
                AND month = %d
                AND year = %d
            ", $user_id, $month, $year ));

            if( isset($get_reports) && !empty($get_reports) ){

                // Redirect user with error, because report already sent
                $arr_params = array( 
                    'page' => 'beitrags-report',
                    'err' => 'already-sent'
                );

            }else{

                // Get all posts by current user id
                $query = array(
                    'posts_per_page' => '-1', 
                    'post_status' => 'publish', 
                    'author' => $user_id,
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
                    $body .= wp_sprintf( __( 'Artikel über 1000 Wörter: %d', 'geldhelden' ), $posts_amount ) . "<br>";
                    $body .= wp_sprintf( __( '- Prüfung der autom. Berechnung: %d', 'geldhelden' ), $articles_over_thousand ) . "<br>";
                    $body .= wp_sprintf( __( 'Blog: %s - %s', 'geldhelden' ), get_bloginfo( 'name' ), get_site_url() );

                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    
                    wp_mail( $to, $subject, $body, $headers );

                }

                // Insert to database
                $insert_request = $wpdb->insert(
                    $wpdb->prefix . 'user_reported_articles',
                    array(
                        'date' => date('Y-m-d H:i:s'),
                        'user_id' => sanitize_text_field($user_id),
                        'month' => sanitize_text_field($month),
                        'year' => sanitize_text_field($year),
                        'posts_amount' => sanitize_text_field($posts_amount)
                    ),
                    array(
                        '%s',
                        '%d',
                        '%d',
                        '%d',
                        '%d'
                    )
                );

                // Redirect user
                $arr_params = array( 
                    'page' => 'beitrags-report',
                    'nor' => $articles_over_thousand
                );

            }

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

/**
 * Notice: global notice for articles report
 */

function report_articles_notice() {

    global $wpdb;
    $user_id = get_current_user_id();
    $day = date('d');
    
    // Display notice only between 28th of last month till 04th of current month
    if( in_array($day, range(5, 27)) ){
        return;
    }

    // Check if user already sent article report for current/last month
    if($day <= 4){
        $month = date('m', strtotime(date('Y-m-d')." -1 month"));
        $year = date('Y', strtotime(date('Y-m-d')." -1 month"));
    }else{
        $month = date('m');
        $year = date('Y');
    }

    $already_sent = $wpdb->get_results( $wpdb->prepare( "
        SELECT * FROM {$wpdb->prefix}user_reported_articles
        WHERE user_id = %d
        AND month = %d
        AND year = %d
    ", $user_id, $month, $year ) );

    if( !empty($already_sent) ){
        return;
    }
    ?>

    <div class="updated notice">
        <p><?php echo wp_sprintf( __('Denke daran deinen <a href="%s">Beitrags-Report</a> für diesen Monat abzuschicken!', 'geldhelden'), admin_url('admin.php?page=beitrags-report') ); ?></p>
    </div>
    
    <?php
}
add_action( 'admin_notices', 'report_articles_notice' );