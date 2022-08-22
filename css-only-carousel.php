<?php
/**
 * Plugin Name:       CSS Only Carousel
 * Description:       A CSS Only Carousel inspired by https://css-tricks.com/css-only-carousel/
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
	$link_thru = $attributes['linkThru'] && ! $attributes['editing'];
	$delay     = $attributes['delay'];
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
		$slides     = '';
		$navigation = '';
		$i          = 1;
		$total      = $query->post_count; // see: https://wordpress.stackexchange.com/a/27117
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( ! has_post_thumbnail() ) {
				continue;
			}
			$prev = $i - 1;
			if ( $prev < 1 ) {
				$prev = $total;
			}
			$next = $i + 1;
			if ( $next > $total ) {
				$next = 1;
			}
			$href                 = get_the_permalink();
			$alt                  = the_title_attribute( array( 'echo' => false ) );
			$slides              .= "<li id='carousel-slide$i' tabindex='0' class='carousel-slide'>
			<div class='carousel-snapper'>";
			$link_thru ? $slides .= "<a href='$href' alt='$alt'>" : '';
			$slides              .= get_the_post_thumbnail( null, 'large' );
			$link_thru ? $slides .= '</a>' : '';
			$slides              .= "</div>
			<a href='#carousel-slide$prev'	class='carousel-prev'>Go to last slide</a>
			<a href='#carousel-slide$next'	class='carousel-next'>Go to next slide</a>
			</li>";
			$navigation          .= "<li class='carousel-navigation-item'>
        		<a href='#carousel-slide$i'
					class='carousel-navigation-button'>Go to slide $i</a>
				</li>";
			$i++;
		}

		add_action(
			'wp_enqueue_scripts',
			function () use ( $delay ) {
				$custom_css = "@media (hover: hover) { .carousel-snapper { animation-duration: ${delay}s; } }";
				wp_add_inline_style( 'coderaaron-css-only-carousel-style', $custom_css );
			}
		);
	}

	// Restore original Post Data
	wp_reset_postdata();

	return sprintf(
		'<section class="carousel alignfull" aria-label="Gallery">
		<ol class="carousel-viewport">
			%1$s
		</ol>
		<aside class="carousel-navigation">
			<ol class="carousel-navigation-list">
				%2$s
			</ol>
		</aside>
		</section>',
		$slides,
		$navigation
	);
}

