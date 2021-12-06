		<div class="clear"></div>
        
		<!-- footer -->
		<footer class="footer" role="contentinfo">
			<?php
			// Check if there are footer widgets.
			if ( is_active_sidebar( 'footer-1' ) or is_active_sidebar( 'footer-2' ) or is_active_sidebar( 'footer-3' ) ) : ?>

				<div id="footer-widgets-wrap" class="footer-widgets-wrap">

					<div id="footer-widgets" class="footer-widgets" role="complementary">
						
						<div class="footer-widget-column widget-area">
							<div class="nomics-ticker-widget" data-name="Moneyhero" data-base="MYH" data-quote="USD"></div><script src="https://widget.nomics.com/embed.js"></script>
						</div>

						<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>

							<div class="footer-widget-column widget-area">
								<?php dynamic_sidebar( 'footer-1' ); ?>
							</div>

						<?php endif; ?>

						<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>

							<div class="footer-widget-column widget-area">
								<?php dynamic_sidebar( 'footer-2' ); ?>
							</div>

						<?php endif; ?>

						<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>

							<div class="footer-widget-column widget-area">
								<?php dynamic_sidebar( 'footer-3' ); ?>
							</div>

						<?php endif; ?>

					</div>

				</div>

			<?php endif; ?>
		</footer>
		<!-- /footer -->

		<?php wp_footer(); ?>

	</body>
</html>
