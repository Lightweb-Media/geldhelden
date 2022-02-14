<?php

/**
 * Functionality class for add/hide title in pages/posts
 *
 */

if ( !class_exists( 'moneyhero_hidepagetitle' ) ) {

    class moneyhero_hidepagetitle {
    	private $moneyhero_slug = 'moneyhero_headertitle';
    	private $moneyhero_selector = '.entry-title';
    	private $title;
    	private $moneyhero_afthead = false;

        function __construct(){
	        add_action( 'add_meta_boxes', array( $this, 'moneyhero_hptaddbox' ) );
			add_action( 'save_post', array( $this, 'moneyhero_hptsave' ) );
			add_action( 'delete_post', array( $this, 'moneyhero_hptdelete' ) );
			add_action( 'wp_head', array( $this, 'moneyhero_hptheadinsert' ) );
			add_action( 'the_title', array( $this, 'moneyhero_hptwraptitle' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'moneyhero_hptloadscripts' ) );
        }

		private function moneyhero_ishidden(  ){	if( is_singular() ){
				global $post;
				$toggle = get_post_meta( $post->ID, $this->moneyhero_slug, true );
				if( (bool) $toggle ){return true;} else {return false;}}
				else {return false;}
				}

    	public function moneyhero_hptheadinsert()
		 { if( $this->moneyhero_ishidden() ){ ?>
              <script type="text/javascript">
				jQuery(document).ready(function($){
				  if( $('<?php echo $this->moneyhero_selector; ?>').length != 0 ) {
					$('<?php echo $this->moneyhero_selector; ?> span.<?php echo $this->moneyhero_slug; ?>').parents('<?php echo $this->moneyhero_selector; ?>:first').hide();
				    } else {
					  $('h1 span.<?php echo $this->moneyhero_slug; ?>').parents('h1:first').hide();
					  $('h2 span.<?php echo $this->moneyhero_slug; ?>').parents('h2:first').hide();
				   }
				});
              </script><noscript><style type="text/css"> <?php echo $this->moneyhero_selector; ?> { display:none !important; }</style></noscript>

	    <?php }$this->moneyhero_afthead = true;
		 }

		public function moneyhero_hptaddbox(){
            $posttypes = array( 'post', 'page' );
            $args = array(
                       'public'   => true,
                       '_builtin' => false,
            );

            $output = 'names';
            $operator = 'and';

            $post_types = get_post_types( $args, $output, $operator ); 

            foreach ( $post_types  as $post_type ) {
              
                $posttypes[] = $post_type;

            }  
                        
			foreach ( $posttypes as $posttype ){add_meta_box( $this->moneyhero_slug, 'Hide Title', array( $this, 'build_hptbox' ), $posttype, 'side' );}
		} 

		public function build_hptbox( $post ){
			$value = get_post_meta( $post->ID, $this->moneyhero_slug, true );
			$checked = '';
			if( (bool) $value ){ $checked = ' checked="checked"'; }
			wp_nonce_field( $this->moneyhero_slug . '_dononce', $this->moneyhero_slug . '_noncename' );	?>
			<label><input type="checkbox" name="<?php echo $this->moneyhero_slug; ?>" <?php echo $checked; ?> /> Hide the title.</label><?php
		}

		public function moneyhero_hptwraptitle( $hptcontent ){
			if( $this->moneyhero_ishidden() && $hptcontent == $this->title && $this->moneyhero_afthead ){$hptcontent = '<span class="' . $this->moneyhero_slug . '">' . $hptcontent . '</span>';
			}return $hptcontent;
		} 

		public function moneyhero_hptloadscripts(){
			global $post;
			$this->title = $post->post_title;
			if( $this->moneyhero_ishidden() ){wp_enqueue_script( 'jquery' );}
		}

		
		public function moneyhero_hptsave( $postID ){
			if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
				|| !isset( $_POST[ $this->moneyhero_slug . '_noncename' ] )
				|| !wp_verify_nonce( $_POST[ $this->moneyhero_slug . '_noncename' ], $this->moneyhero_slug . '_dononce' ) ) {
				return $postID;
			}
			$old = get_post_meta( $postID, $this->moneyhero_slug, true );
			$new = $_POST[ $this->moneyhero_slug ] ;
			if( $old ){if ( is_null( $new ) ){delete_post_meta( $postID, $this->moneyhero_slug );} else { update_post_meta( $postID, $this->moneyhero_slug, $new, $old );}
			} elseif ( !is_null( $new ) ){add_post_meta( $postID, $this->moneyhero_slug, $new, true );}
			return $postID;
		}

		public function moneyhero_hptdelete( $postID ){delete_post_meta( $postID, $this->moneyhero_slug );return $postID;}
		public function set_moneyhero_selector( $moneyhero_selector ){if( isset( $moneyhero_selector ) && is_string( $moneyhero_selector ) ){$this->moneyhero_selector = $moneyhero_selector;}
		}

    }
    $moneyhero_hidepagetitle = new moneyhero_hidepagetitle;
}