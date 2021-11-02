<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			    <?php the_post_thumbnail('full'); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->

        <div class="post-meta-box">
            
            <!-- post author -->
            <?php
            $fname = get_the_author_meta('first_name');
            $lname = get_the_author_meta('last_name');
            $full_name = $fname . " " . $lname;

            if( strlen($full_name) > 1 ){ ?>
                <div class="author-profile-box">
                    <div class="author-profile-image"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a></div>
                    <div class="author-profile-name"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo trim( $full_name ); ?></a></div>
                </div>
            <?php } ?>
            <!-- /post author -->

            <!-- post date -->
            <div class="loop-post-date">
                <span class="date"><?php the_time('d. F Y'); ?></span>
            </div>
            <!-- /post date -->

        </div>

		<!-- post title -->
		<h2 class="loop-post-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->

		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

        <!-- post categories / tags -->
        <?php $categories = get_the_category(); ?>
        <?php $tags = get_the_tags(); ?>
        <?php $i = 1; ?>
		<ul class="loop-post-tags">

            <?php if( $categories ){ ?>
                <?php foreach( $categories as $category ){ ?>
			        <li <?php echo ( $i > 5 ? 'class="li-hidden"' : ''); ?>><a href="<?php echo get_term_link($category->term_id); ?>"><?php echo $category->name; ?></a></li>
                    <?php $i++; ?>
                <?php } ?>
            <?php } ?>

            <?php if( $tags ){ ?>
                <?php foreach( $tags as $tag ){ ?>
			        <li <?php echo ( $i > 5 ? 'class="li-hidden"' : ''); ?>><a href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a></li>
                    <?php $i++; ?>
                <?php } ?>
            <?php } ?>

            <!-- Add Button with [...] if more than 5 categories / tags -->
            <?php if( $i > 5 ){ ?>
                <li><a class="show-all-tags">...</a></li>
            <?php } ?>

        </ul>
		<!-- /post categories / tags -->
        
        <div class="clear"></div>

		<!-- post details -->
        <a class="comment-link" href="<?php echo get_permalink() . "#post-comments"; ?>" title="<?php the_title() . " - " . __('Kommentare', 'geldhelden'); ?>">
            <div class="comments-box">
                <span class="comments"><img src="<?php echo get_template_directory_uri(); ?>/img/icons/comment-alt-regular.svg"><?php echo get_comments_number(); ?></span>
            </div>
        </a>
        <!-- /post details -->

	</article>
	<!-- /article -->

<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Keine Artikel gefunden.', 'geldhelden' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
