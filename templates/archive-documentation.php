<?php
/**
 * The template for displaying all single documentation.
 *
 * @package lsx-documentation
 */

get_header(); ?>

<?php lsx_content_wrap_before(); ?>

<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

	<?php lsx_content_before(); ?>

	<main id="main" class="site-main">

		<?php lsx_content_top(); ?>

		<?php
			$args = array(
				'taxonomy'   => 'documentation-category',
				'hide_empty' => false,
			);

			$doc_categories = get_terms( $args );
			$doc_categories_selected = get_query_var( 'documentation-category' );

			if ( count( $doc_categories ) > 0 ) :
			?>

			<ul class="nav nav-tabs lsx-documentation-filter">
				<?php
					$doc_categories_selected_class = '';

					if ( empty( $doc_categories_selected ) ) {
						$doc_categories_selected_class = ' class="active"';
					}
				?>

				<li<?php echo wp_kses_post( $doc_categories_selected_class ); ?>><a href="<?php echo empty( $doc_categories_selected ) ? '#' : esc_url( get_post_type_archive_link( 'documentation' ) ); ?>" data-filter="*"><?php esc_html_e( 'All', 'lsx-documentation' ); ?></a></li>

				<?php foreach ( $doc_categories as $doc_category ) : ?>
					<?php
						$doc_categories_selected_class = '';

						if ( (string) $doc_categories_selected === (string) $doc_category->slug ) {
							$doc_categories_selected_class = ' class="active"';
						}
					?>

					<li<?php echo wp_kses_post( $doc_categories_selected_class ); ?>><a href="<?php echo empty( $doc_categories_selected ) ? '#' : esc_url( get_term_link( $doc_category ) ); ?>" data-filter=".filter-<?php echo esc_attr( $doc_category->slug ); ?>"><?php echo esc_attr( $doc_category->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>

			<?php
			endif;
		?>

		<?php if ( have_posts() ) : ?>

			<div class="lsx-documentation-container">
				<div class="row row-flex lsx-documentation-row"">

					<?php
						$count = 0;

						while ( have_posts() ) {
							the_post();
							include( LSX_DOCUMENTATION_PATH . '/templates/content-archive-documentation.php' );
						}
					?>

				</div>
			</div>

			<?php lsx_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		<?php lsx_content_bottom(); ?>

	</main><!-- #main -->

	<?php lsx_content_after(); ?>

</div><!-- #primary -->

<?php lsx_content_wrap_after(); ?>

<?php get_sidebar(); ?>

<?php get_footer();
