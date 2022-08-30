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

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		$inputs     = '';
		$controls   = '';
		$slides     = '';
		$indicators = '';
		$custom_css = '';

		$i     = 1;
		$total = $query->post_count; // see: https://wordpress.stackexchange.com/a/27117

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
			$img  = get_the_post_thumbnail_url( get_the_ID(), 'large' );

			$checked = ( 1 === $i ) ? ' checked="checked"' : '';

			$inputs .= "<input class='carousel-activator' type='radio' name='carousel' id='slide-$i'$checked/>";

			$controls .= "<div class='carousel-controls'>
				<label class='carousel-control carousel-control-backward' for='slide-$prev'></label>
				<label class='carousel-control carousel-control-forward' for='slide-$next'></label>
			</div>";

			$slides              .= '<li class="carousel-slide">';
			$link_thru ? $slides .= "<a href='$href'>$alt</a>" : '';
			$slides              .= '</li>';

			$indicators .= "<label class='carousel-indicator' for='slide-$i'></label>";

			$custom_css .= ".carousel-slide:nth-of-type($i),
			.carousel-thumb .carousel-indicators .carousel-indicator:nth-of-type($i) {
				background-image: url($img);
			}

			.carousel-track .carousel-slide:nth-of-type($i) {
				transform: translateX(" . ( $i - 1 ) * 100 . "%);
			}

			.carousel-activator:nth-of-type($i):checked ~ .carousel-track {
				transform: translateX(" . ( $i - 1 ) * -100 . "%);
			}
			.carousel-activator:nth-of-type($i):checked ~ .carousel-controls:nth-of-type($i) {
				display: block;
				opacity: 1;
			}
			.carousel-activator:nth-of-type($i):checked ~ .carousel-indicators .carousel-indicator:nth-of-type($i) {
				opacity: 1;
			}";

			$i++;
		}

		add_action(
			'enqueue_block_editor_assets',
			function () use ( $custom_css ) {
				wp_add_inline_style( 'coderaaron-css-only-carousel-style', $custom_css );
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () use ( $custom_css ) {
				wp_add_inline_style( 'coderaaron-css-only-carousel-style', $custom_css );
			}
		);
	}

	// Restore original Post Data
	wp_reset_postdata();

	$dots_html = '';
	switch ( $dot_type ) {
		case 'dots':
			break;
		case 'thumbs':
			$dots_html = 'carousel-thumb';
			break;
		default:
			$dots_html = 'carousel-none';
	}

	return sprintf(
		'<div class="alignfull carousel %1$s">
			%2$s
			%3$s
			<ul class="carousel-track">
				%4$s
			</ul>
			<div class="carousel-indicators">
				%5$s
			</div>
		</div>',
		$dots_html,
		$inputs,
		$controls,
		$slides,
		$indicators,
	);
}
