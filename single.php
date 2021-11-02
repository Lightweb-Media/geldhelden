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

            <!-- post author -->
            <?php
            $fname = get_the_author_meta('first_name');
            $lname = get_the_author_meta('last_name');
            $full_name = $fname . " " . $lname;
            $description = get_the_author_meta('description');

            if( strlen($full_name) > 1 ){ 
                if(!empty($description)){ ?>
                    <div class="author-profile-box">
                        <div class="author-big-profile-image"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a></div>
                        <div class="author-profile-name">
                            <h3><?php echo trim( $fname . " " . $lname ); ?></h3>
                            <p class="author-description"><?php echo the_author_meta('description'); ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
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

                        <?php
                            $fname = get_the_author_meta('first_name');
                            $lname = get_the_author_meta('last_name');
                            $full_name = $fname . " " . $lname;
                        ?>

                        <h4><?php the_title(); ?></h4>
                        <?php html5wp_excerpt('related_excerpt_length'); ?>

                        <?php if( strlen($full_name) > 1 ){ ?>
                            <div class="related-author-wrapper">
                                <div class="author-small-profile-box">
                                    <div class="author-small-profile-image"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?></a></div>
                                    <div class="author-small-profile-name">
                                        <span class="small-author-name"><?php echo trim( $fname . " " . $lname ); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
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