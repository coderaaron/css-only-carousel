<?php
/**
 * Plugin Name:       CSS Only Carousel
 * Description:       A CSS Only Carousel inspired by https://css-tricks.com/css-only-carousel/
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Aaron Graham
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       css-only-carousel
 *
 * @package           create-block
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

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 */
function register_css_only_carousel_meta() {
	// Register the post meta field the meta box will save to.
	register_post_meta(
		'post',
		'use_in_carousel',
		array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'boolean',
		)
	);
}
add_action( 'init', 'register_css_only_carousel_meta' );

function css_only_carousel_block_render( $attributes ) {
	// WP_Query arguments
	$args = array(
		'posts_per_page' => '-1',
		'meta_query'     => array(
			array(
				'key'   => 'use_in_carousel',
				'value' => '1',
				'type'  => 'BINARY',
			),
		),
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
			$prev = $i - 1;
			if ( $prev < 1 ) {
				$prev = $total;
			}
			$next = $i + 1;
			if ( $next > $total ) {
				$next = 1;
			}
			$slides     .= "<li id='carousel__slide$i'
				tabindex='0'
				class='carousel__slide'>
			<div class='carousel__snapper'>
				<a href='#carousel__slide$prev'
				class='carousel__prev'>Go to last slide</a>
				<a href='#carousel__slide$next'
				class='carousel__next'>Go to next slide</a>
			</div>
			</li>";
			$navigation .= "<li class='carousel__navigation-item'>
        		<a href='#carousel__slide$i'
					class='carousel__navigation-button'>Go to slide $i</a>
				</li>";
			$i++;
		}
	}

	// Restore original Post Data
	wp_reset_postdata();

	return sprintf(
		'<section class="carousel" aria-label="Gallery">
		<ol class="carousel__viewport">
			%1$s
		</ol>
		<aside class="carousel__navigation">
			<ol class="carousel__navigation-list">
				%2$s
			</ol>
		</aside>
		</section>',
		$slides,
		$navigation
	);
}

