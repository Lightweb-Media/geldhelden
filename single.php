<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- return to main page -->
			<button class="back-button" onclick="history.back(-1)">&#171; Zurück zu allen Beiträgen</button>

			<!-- post thumbnail -->
			<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				</a>
			<?php endif; ?>
			<!-- /post thumbnail -->

			<!-- post title -->
			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h1>
			<!-- /post title -->

			<!-- post details -->
			<span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
			<span class="author"><?php _e( 'Veröffentlicht von', 'geldhelden' ); ?> <?php the_author_posts_link(); ?></span>
			<!-- /post details -->

			<?php the_content(); // Dynamic Content ?>			
			
			<!-- return to main page -->
			<button class="back-button" onclick="history.back(-1)">&#171; Zurück zu allen Beiträgen</button>

			<?php the_tags( __( 'Tags: ', 'geldhelden' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>

			<p><?php _e( 'Kategorien: ', 'geldhelden' ); the_category(', '); // Separated by commas ?></p>

			<?php edit_post_link(); // Always handy to have Edit Post Links available ?>

			<?php comments_template(); ?> **/ ?>

		</article>
		<!-- /article -->

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
