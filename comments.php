<div id="post-comments" class="comments">
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Der Beitrag ist passwortgesichert. Bitte geben Sie das Passwort ein.', 'geldhelden' ); ?></p>
</div>

	<?php return; endif; ?>

<?php if (have_comments()) : ?>

	<h2><?php comments_number(); ?></h2>

	<ul>
		<?php wp_list_comments('type=comment&callback=html5blankcomments'); // Custom callback in functions.php ?>
	</ul>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p><?php _e( 'Kommentare nicht erlaubt.', 'geldhelden' ); ?></p>

<?php endif; ?>

<?php comment_form(); ?>

</div>
