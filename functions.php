<?php
/*
	Theme Name: MoneyHero
	Theme URI: https://github/Lightweb-Media/moneyhero
	Description: MoneyHero Theme 
	Version: 2.1.5
	Author: Lightweb Media
	Author URI: https://lightweb-media.de
	Tags: Blank, HTML5, CSS3
	License: MIT
	License URI: http://opensource.org/licenses/mit-license.php
*/

define ('GELDHELDEN_DIR',get_template_directory());
require_once(GELDHELDEN_DIR .'/inc/plugin-update-checker/plugin-update-checker.php');

$GeldheldenUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/lightweb-media/moneyhero/',
	__FILE__, //Full path to the main plugin file or functions.php.
	'moneyhero'
);

$GeldheldenUpdateChecker->setBranch('main');
if (!isset($content_width))
{
    $content_width = 900;
}

// Redirect in backend, if language not set
function redirect_if_lang_not_set(){

    if ( $GLOBALS['pagenow'] != 'wp-login.php' && $GLOBALS['pagenow'] != 'options.php' && is_admin() ) {
        $geldhelden_language = esc_attr( get_option('geldhelden_language') );

        if( empty($geldhelden_language) ){

            if( isset( $GLOBALS['_GET']['page'] ) && $GLOBALS['_GET']['page'] == 'geldhelden' ){
                return;
            }

            wp_redirect( admin_url('/admin.php?page=geldhelden', 'https') );
            exit;

        }
    }   
}
add_action('init', 'redirect_if_lang_not_set');

// Change Language
function change_global_locale($locale) {
    $geldhelden_language = esc_attr( get_option('geldhelden_language') );

    if( isset($geldhelden_language) ){
        switch_to_locale($geldhelden_language);
    }
}
add_filter('init','change_global_locale');

add_filter('locale', function($locale) {

    $geldhelden_language = esc_attr( get_option('geldhelden_language') );
    
    if( isset($geldhelden_language) ){
        return $geldhelden_language;
    }else{
        return $locale;
    }

});

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('geldhelden', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    	// Bootstrap
        // wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css' );

        // Conditionizr
        wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
        wp_enqueue_script('conditionizr');

        // Modernizr
        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr');

        // Custom JS
        wp_register_script('customjs', get_template_directory_uri() . '/js/custom.js', array('jquery'));
        wp_enqueue_script('customjs');

    }
}

// Load HTML5 Blank styles
function html5blank_styles()
{
    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize');

    wp_register_style('mainstyle', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('mainstyle');
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menü', 'geldhelden'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menü', 'geldhelden'), // Sidebar Navigation
        'extra-menu' => __('Extra Menü', 'geldhelden') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'geldhelden'),
        'description' => __('Beschreibung Widget Area 1', 'geldhelden'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'geldhelden'),
        'description' => __('Beschreibung Widget Area 2', 'geldhelden'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Create the Custom Excerpts length
function moneyhero_excerpt($length, $link = false)
{
    global $post;
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . trim(mb_substr($output, 0, $length, 'UTF-8')) . ( $link == true ? '<a class="view-article" href="' . get_permalink($post->ID) . '">...' . __('Weiterlesen', 'geldhelden') . '</a>' : '' ) . '</p>';
    echo $output;
}

function my_custom_excerpt( $length ) {
    $custom = get_theme_mod( 'custom_excerpt_length' );
    if( $custom != '' ) {
        return $length = intval( $custom );
    } else {
        return $length;
    }
}
add_filter( 'excerpt_length', 'my_custom_excerpt', 999 );

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '<a class="view-article" href="' . get_permalink($post->ID) . '">...' . __('Weiterlesen', 'geldhelden') . '</a>';
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{

	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
        <?php 
        $profile_img = get_user_meta( $comment->user_id, 'profile_img', true );
        if ($profile_img){ echo "<img src='" . esc_url_raw($profile_img) . "' height='96' width='96' loading='lazy' class='avatar avatar-96 photo'>"; } 
        ?>
        <div class="commtent-details">
            <?php printf(__('<cite class="fn">%s</cite> <span class="time-elapsed">%s</span>', 'geldhelden'), get_comment_author_link(), smk_get_comment_time(get_comment_ID())) ?>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Ihr Kommentar wartet auf die Freischaltung.', 'geldhelden') ?></em>
                    <br />
                <?php endif; ?>

                <?php comment_text() ?>

                <div class="reply">
                    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </div>
        </div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/* Add GA variable */
function create_analytics_link( $link ){

    $site_title = get_bloginfo( 'name' );
    $site_title = str_replace( ' ', '', $site_title );

    $new_link = add_query_arg( array(
        'utm_source' => esc_attr($site_title),
        'utm_medium' => 'theme',
        'utm_campaign' => 'theme'
    ), $link );

    return esc_url_raw( $new_link );

}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Require: Report Articles Form
require_once(GELDHELDEN_DIR .'/backend/report-articles-form.php');

// Require: Theme settings
require_once(GELDHELDEN_DIR .'/backend/theme-settings.php');

// Require: Profile img
require_once(GELDHELDEN_DIR .'/backend/add-profile-img.php');

// Add Footer Widget Areas
function theme_slug_register_footer_widgets() {
	// Register Footer Column 1 widget area.
	register_sidebar( array(
		'name' => __( 'Footer Column 1', 'geldhelden' ),
		'id' => 'footer-1',
		'description' => __( 'Appears on the first footer column.', 'geldhelden' ),
		'before_widget' => '<aside id="%1$s" class="footer-block-1 widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Register Footer Column 2 widget area.
	register_sidebar( array(
		'name' => __( 'Footer Column 2', 'geldhelden' ),
		'id' => 'footer-2',
		'description' => __( 'Appears on the second footer column.', 'geldhelden' ),
		'before_widget' => '<aside id="%1$s" class="footer-block-2 widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Register Footer Column 3 widget area.
	register_sidebar( array(
		'name' => __( 'Footer Column 3', 'geldhelden' ),
		'id' => 'footer-3',
		'description' => __( 'Appears on the third footer column.', 'geldhelden' ),
		'before_widget' => '<aside id="%1$s" class="footer-block-3 widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Register Footer Column 3 widget area.
	register_sidebar( array(
		'name' => __( 'Footer Column 3', 'geldhelden' ),
		'id' => 'footer-3',
		'description' => __( 'Appears on the third footer column.', 'geldhelden' ),
		'before_widget' => '<aside id="%1$s" class="footer-block-3 widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'theme_slug_register_footer_widgets', 20 );

// Time Elapsed
function smk_get_comment_time( $comment_id = 0 ){
    return sprintf( 
        _x( '%s', 'Human-readable time', 'geldhelden' ), 
        human_time_diff( 
            get_comment_date( 'U', $comment_id ), 
            current_time( 'timestamp' ) 
        ) 
    );
}
