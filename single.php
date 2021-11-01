<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- return to main page -->
			<button class="back-button" onclick="history.back(-1)">&#171; <?php _e('Zurück zu allen Beiträgen', 'geldhelden'); ?></button>

			<!-- post thumbnail -->
			<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				</a>
			<?php endif; ?>
			<!-- /post thumbnail -->

			<!-- post title -->
			<h1><?php the_title(); ?></h1>
			<!-- /post title -->

			<?php the_content(); // Dynamic Content ?>			

            <!-- categories & tags -->
            <?php $categories = get_categories(); ?>
            <ul class="loop-post-tags">

                <?php if( $categories ){ ?>
                    <?php foreach( $categories as $category ){ ?>
                        <li><a href="<?php echo get_term_link($category->term_id); ?>"><?php echo $category->name; ?></a></li>
                    <?php } ?>
                <?php } ?>
                
			    <?php the_tags( '<li>', '</li><li>', '</li>' ); // Separated by commas with a line break at the end ?>
                
            </ul>
            <!-- /categories & tags -->

            <div class="clear"></div>

            <!-- post author -->
            <?php
            $fname = get_the_author_meta('first_name');
            $lname = get_the_author_meta('last_name');
            ?>
            <div class="author-profile-box">
                <div class="author-big-profile-image"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar( get_the_author_meta('email'), '128', '/images/no_images.jpg', get_the_author() ); ?></a></div>
                <div class="author-profile-name">
                    <h3><?php echo trim( $fname . " " . $lname ); ?></h3>
                    <p class="author-description"><?php echo the_author_meta('description'); ?></p>
                </div>
            </div>
			<!-- /post author -->

			<?php comments_template(); ?>

			<!-- return to main page -->
			<button class="back-button" onclick="history.back(-1)">&#171; <?php _e('Zurück zu allen Beiträgen', 'geldhelden'); ?></button>

		</article>
		<!-- /article -->

        <!-- related articles -->
        <div id="related-articles">
            <h2><?php _e('Weitere Beiträge', 'geldhelden'); ?></h2>
            <ul id="related-articles-list"> 
                <?php
                $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 5, 'post__not_in' => array($post->ID) ) );
                if( $related ) foreach( $related as $post ) {
                setup_postdata($post); ?>
                <li>
                    <a class="post-grid-item-inner" href="<?php the_permalink() ?>" title="<?php the_title(); ?>">

                        <?php if ( has_post_thumbnail()) : ?>
                            <div class="realted-item-img" style="background: url(<?php the_post_thumbnail_url(); ?>);"></div>
                        <?php endif; ?>

                        <h4><?php the_title(); ?></h4>
                        <?php html5wp_excerpt('related_excerpt_length'); ?>

                        <div class="related-author-wrapper">
                            <?php
                            $fname = get_the_author_meta('first_name');
                            $lname = get_the_author_meta('last_name');
                            ?>
                            <div class="author-small-profile-box">
                                <div class="author-small-profile-image"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar( get_the_author_meta('email'), '128', '/images/no_images.jpg', get_the_author() ); ?></a></div>
                                <div class="author-small-profile-name">
                                    <span class="small-author-name"><?php echo trim( $fname . " " . $lname ); ?></span>
                                </div>
                            </div>
                        </div>
                        
                    </a>
                </li>
                <?php }
                wp_reset_postdata(); ?>
            </ul>   
        </div>
        <!-- /related articles -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Keine Artikel gefunden.', 'geldhelden' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>

	</section>
	<!-- /section -->
	</main>

<?php get_footer(); ?>
