<?php
/**
 * Plugin Name:       CSS Only Carousel
 * Description:       A CSS Only Carousel inspired by https://codepen.io/jh3y/pen/WwVKLN
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.1.0
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

function css_only_carousel_block_render( $attributes, $content, $block_instance ) {
	$link_thru   = $attributes['linkThru'] && ! $attributes['editing'];
	$auto_play   = $attributes['autoPlay'];
	$delay       = $attributes['delay'];
	$show_arrows = $attributes['showArrows'];
	$dot_type    = $attributes['dotType'];
	$transition  = $attributes['transition'];
	$block_id    = $attributes['id'];

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
		$inline_js  = '';

		$i     = 1;
		$total = $query->post_count; // see: https://wordpress.stackexchange.com/a/27117

		// If we only have one image, no need to display navigation related things
		if ( $total < 2 ) {
			$show_arrows = false;
			$dot_type    = 'none';
		}

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
			$img  = get_the_post_thumbnail( get_the_ID(), 'full' );

			$checked = ( 1 === $i ) ? ' checked="checked"' : '';

			$inputs .= "<input class='carousel-activator' type='radio' name='carousel-$block_id' value='$i' id='slide-$i-$block_id'$checked/>";

			$show_arrows ? $controls .= "<div class='carousel-controls'>
				<label class='carousel-control carousel-control-backward' for='slide-$prev-$block_id'>&laquo;</label>
				<label class='carousel-control carousel-control-forward' for='slide-$next-$block_id'>&raquo;</label>
			</div>" : '';

			$slides              .= '<li class="carousel-slide">';
			$link_thru ? $slides .= "<a href='$href'>" : '';
			$slides              .= $img;
			$link_thru ? $slides .= '</a>' : '';
			$slides              .= '</li>';

			$thumbnail   = ( 'thumbs' === $dot_type ) ? $img : '';
			$indicators .= "<label class='carousel-indicator' for='slide-$i-$block_id'>$thumbnail</label>";

			$custom_css .= ".carousel-$block_id .carousel-track .carousel-slide:nth-of-type($i) {
				transform: translateX(" . ( $i - 1 ) * 100 . "%);
			}

			.carousel-$block_id .carousel-activator:nth-of-type($i):checked ~ .carousel-track {
				transform: translateX(" . ( $i - 1 ) * -100 . "%);
			}
			.carousel-$block_id .carousel-activator:nth-of-type($i):checked ~ .carousel-slide:nth-of-type($i) {
				transition: opacity 0.5s, transform 0.5s;
				top: 0;
				left: 0;
				right: 0;
				opacity: 1;
				transform: scale(1);
			}
			.carousel-$block_id .carousel-activator:nth-of-type($i):checked ~ .carousel-controls:nth-of-type($i) {
				display: block;
				opacity: 1;
			}
			.carousel-$block_id .carousel-activator:nth-of-type($i):checked ~ .carousel-indicators .carousel-indicator:nth-of-type($i) {
				opacity: 1;
			}";

			$i++;
		}

		if ( true === $auto_play ) {
			$inline_js = 'window.addEventListener("load", (event) => {const n=document.getElementsByName("carousel-' . $block_id . '").length;setInterval(()=>{let c=parseInt(document.querySelector("input[name=carousel-' . $block_id . ']:checked").value);c=c!==n?c+1:1;document.getElementById("slide-"+c+"-' . $block_id . '").checked=true;},' . $delay * 1000 . ');});';
			//$inline_js = 'window.onload=()=>{const n=document.getElementsByName("carousel-' . $block_id . '").length;setInterval(()=>{let c=parseInt(document.querySelector("input[name=carousel-' . $block_id . ']:checked").value);c=c!==n?c+1:1;document.getElementById("slide-"+c+"-' . $block_id . '").checked=true;},' . $delay * 1000 . ');};';
		}

		add_action(
			'enqueue_block_editor_assets',
			function () use ( $custom_css ) {
				wp_add_inline_style( 'coderaaron-css-only-carousel-style', $custom_css );
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () use ( $custom_css, $inline_js ) {
				wp_add_inline_script( 'coderaaron-css-only-carousel-script', $inline_js );
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
			$dots_html = ' carousel-thumb';
			break;
		default:
			$dots_html = ' carousel-none';
	}

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => "carousel carousel-$block_id$dots_html",
		)
	);

	$transition_open  = '';
	$transition_close = '';
	switch ( $transition ) {
		case 'fade':
			break;
		case 'slide':
			$transition_open  = '<ul class="carousel-track">';
			$transition_close = '</ul>';
			break;
		default:
			break;
	}

	return sprintf(
		'<div %1$s">
			%2$s
			%3$s
			%4$s
				%5$s
			%6$s
			<div class="carousel-indicators">
				%7$s
			</div>
		</div>',
		$wrapper_attributes,
		$inputs,
		$controls,
		$transition_open,
		$slides,
		$transition_close,
		$indicators,
	);
}
