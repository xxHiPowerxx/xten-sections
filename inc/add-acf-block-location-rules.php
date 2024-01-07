<?php

/**
 * ACF Rule Type: Block Category
 *
 * @author Ricky Adams
 * @see http://www.billerickson.net/acf-custom-location-rules
 *
 * @param array $choices, all of the available rule types
 * @return array
 */
function xten_acf_rule_block_category( $choices ) {
	$choices['Forms']['block_category'] = 'Block Category';
	return $choices;
}
add_filter( 'acf/location/rule_types', 'xten_acf_rule_block_category' );

/**
 * ACF Rule Values: Block Category
 *
 * @author Ricky Adams
 * @see http://www.billerickson.net/acf-custom-location-rules
 *
 * @param array $choices, available rule values for this type
 * @return array
 */
function xten_acf_rule_values_block_category( $choices ) {

	// Copied from acf/pro/locations/class-acf-location-block.php
		
	// vars
	$blocks = acf_get_block_types();
	
	// loop
	if( $blocks ) {
		$choices['all'] = __('All', 'acf');

		// Get the Title of the Block Category.
		// Get ALL block Categories.
		$block_categories = get_block_categories(get_posts());
		$unique_categories = array();
		// Store each Unique Title.
		// $unique_categories = array('category-slug' => 'Category Title')
		foreach ( $block_categories as $block_category ) :
			$unique_categories[$block_category['slug']] = $block_category['title'];
		endforeach;
		foreach( $blocks as $block ) :
			$choices[ $block['category'] ] = $unique_categories[$block['category']];
		endforeach;
	}	
	
	// return
	return $choices;
}
add_filter( 'acf/location/rule_values/block_category', 'xten_acf_rule_values_block_category' );

/**
 * ACF Rule Match: Block Category
 *
 * @author Ricky Adams
 * @see http://www.billerickson.net/acf-custom-location-rules
 *
 * @param boolean $match, whether the rule matches (true/false)
 * @param array $rule, the current rule you're matching. Includes 'param', 'operator' and 'value' parameters
 * @param array $options, data about the current edit screen (post_id, page_template...)
 * @return boolean $match
 */
function xten_acf_rule_match_block_category( $match, $rule, $options ) {
	if ( ! $options['post_id'] || 'page' !== get_post_type( $options['post_id'] ) )
		return false;

	$block_categories = get_block_categories( $options['post_id'], 'page' );
	$category_slugs = array_column( $block_categories, 'slug');

	// $is_category = in_array( $rule['value'], $block_categories );
	$is_category = in_array( $rule['value'], $category_slugs );
	
	if ( '==' == $rule['operator'] ) { 
		$match = $is_category;
	
	} elseif ( '!=' == $rule['operator'] ) {
		$match = ! $is_category;
	}
	
	return $match;

}
add_filter( 'acf/location/rule_match/block_category', 'xten_acf_rule_match_block_category', 10, 3 );