<?php

/**
 * Misc functions to use throughout the plugin.
 *
 * @package    PIB
 * @subpackage Includes
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Google Analytics campaign URL.
 *
 * @since     2.0.0
 *
 * @param   string  $base_url Plain URL to navigate to
 * @param   string  $source   GA "source" tracking value
 * @param   string  $medium   GA "medium" tracking value
 * @param   string  $campaign GA "campaign" tracking value
 * @return  string  $url     Full Google Analytics campaign URL
 */
function pib_ga_campaign_url( $base_url, $source, $medium, $campaign ) {
	// $source is always 'pib_lite_2' for Pit It Button Lite 2.x
	// $medium examples: 'sidebar_link', 'banner_image'

	$url = add_query_arg( array(
		'utm_source'   => $source,
		'utm_medium'   => $medium,
		'utm_campaign' => $campaign
	), $base_url );

	return $url;
}

/**
 * Render RSS items from pinterestplugin.com in unordered list.
 * http://codex.wordpress.org/Function_Reference/fetch_feed
 *
 * @since   2.0.0
 */

function pib_rss_news() {
	// Get RSS Feed(s).
	include_once( ABSPATH . WPINC . '/feed.php' );

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( 'http://pinterestplugin.com/feed/' );

	if ( ! is_wp_error( $rss ) ) {
		// Checks that the object is created correctly.
		// Figure out how many total items there are, but limit it to 5.
		$maxitems = $rss->get_item_quantity( 3 );

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $maxitems );
	}
	?>

	<ul>
		<?php if ($maxitems == 0): ?>
			<li><?php _e( 'No items.', 'pib' ); ?></li>
		<?php else: ?>
			<?php
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ): ?>
				<?php $post_url = add_query_arg( array(

					// Google Analytics campaign URL
					'utm_source'   => 'pib_lite_2',
					'utm_medium'   => 'sidebar_link',
					'utm_campaign' => 'blog_post_link'

				), esc_url( $item->get_permalink() ) ); ?>

				<li>
					&raquo; <a href="<?php echo $post_url; ?>" target="_blank" class="pib-external-link"><?php echo esc_html( $item->get_title() ); ?></a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

	<?php
}

/**
 * Check if the WooCommerce plugin is active.
 *
 * @since   2.0.0
 *
 * @return  boolean
 */
function pib_is_woo_commerce_active() {
	return class_exists( 'WooCommerce' );

	/*
	 * Could also do:
	 * if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	 *
	 * References:
	 * http://docs.woothemes.com/document/create-a-plugin/
	 * http://www.wpmayor.com/articles/how-to-check-whether-a-plugin-is-active/
	 * http://pippinsplugins.com/checking-dependent-plugin-active/
	 */
}

/**
 * Check if the Article Rich Pins plugin is active.
 *
 * @since   2.0.2
 *
 * @return  boolean
 */
function pib_is_article_rich_pins_active() {
	return class_exists( 'Article_Rich_Pins' );
}

/**
 * Check if the WooCommerce Rich Pins plugin is active.
 *
 * @since   2.0.2
 *
 * @return  boolean
 */
function pib_is_wc_rich_pins_active() {
	return class_exists( 'WooCommerce_Rich_Pins' );
}
