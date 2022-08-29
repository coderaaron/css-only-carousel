<?php
/**
 * Plugin Name:       CSS Only Carousel
 * Description:       A CSS Only Carousel inspired by https://codepen.io/jh3y/pen/WwVKLN
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Aaron Graham
 * Author URI:        https://coderaaron.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       css-only-carousel
 *
 * @package           coderaaron
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function css_only_carousel_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'css_only_carousel_block_render',
		)
	);
}
add_action( 'init', 'css_only_carousel_block_init' );

function css_only_carousel_block_render( $attributes ) {
	$link_thru   = $attributes['linkThru'] && ! $attributes['editing'];
	$delay       = $attributes['delay'];
	$show_arrows = $attributes['delay'];
	$dot_type    = $attributes['dotType'];

	if ( ! isset( $attributes['selectedPosts'] ) ||
	( is_array( $attributes['selectedPosts'] ) && count( $attributes['selectedPosts'] ) === 0 ) ) {
		return '<section class="carousel alignfull" aria-label="Gallery">No Posts Selected</section>';
	}

	// WP_Query arguments
	$args = array(
		'posts_per_page'      => '-1',
		'post__in'            => $attributes['selectedPosts'],
		'ignore_sticky_posts' => 1,
		'orderby'             => 'post__in',
	);

	// WP_Query arguments
	$args = array(
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
	);

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		require 'Stylus.php';

		$stylus = new Stylus\Stylus();
		$stylus->setReadDir( plugin_dir_path( __FILE__ ) );

		$inputs     = '';
		$controls   = '';
		$slides     = '';
		$indicators = '';

		$i     = 1;
		$total = $query->post_count; // see: https://wordpress.stackexchange.com/a/27117

		$stylus->assign( 'noOfSlides', $total );
		$stylus->assign( 'slideTransition', '.5s' );

		while ( $query->have_posts() ) {
			$query->the_post();
			if ( ! has_post_thumbnail() ) {
				continue; }
			$prev = $i - 1;
			if ( $prev < 1 ) {
				$prev = $total; }
			$next = $i + 1;
			if ( $next > $total ) {
				$next = 1; }
			$href = get_the_permalink();
			$alt  = the_title_attribute( array( 'echo' => false ) );

			$checked = ( 1 === $i ) ? ' checked="checked"' : '';

			$inputes = "<input class='carousel-activator' type='radio' name='carousel' id='slide-$i'$checked/>";

			$controls .= "<div class='carousel-controls'>
				<label class='carousel-control carousel-control-backward' for='slide-$prev'></label>
				<label class='carousel-control carousel-control-forward' for='slide-$next'></label>
			</div>";

			$slides              .= '<li class="carousel-slide">';
			$link_thru ? $slides .= "<a href='$href'>$alt</a>" : '';
			$slides              .= '</li>';

			$indicators .= "<label class='carousel-indicator' for='slide-$i'></label>";

			$i++;
		}

		$custom_css = $stylus->fromFile( 'style.styl' )->toString();

		add_action(
			'wp_enqueue_scripts',
			function () use ( $custom_css ) {
				wp_add_inline_style( 'coderaaron-css-only-carousel-style', $custom_css );
			}
		);
	}

	// Restore original Post Data
	wp_reset_postdata();
	return sprintf(
		'<div class="alignfull carousel carousel-thumb">
			%1$s
			%2$s
			<ul class="carousel-track">
				%3$s
			</ul>
			<div class="carousel-indicators">
				%4$s
			</div>
		</div>',
		$inputs,
		$controls,
		$slides,
		$indicators,
	);
}

