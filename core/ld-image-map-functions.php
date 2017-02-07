<?php
/**
 * Provides helper functions.
 *
 * @since	  1.0.0
 *
 * @package	LD_Image_Map
 * @subpackage LD_Image_Map/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		1.0.0
 *
 * @return		LD_Image_Map
 */
function LDIMAGEMAP() {
	return LD_Image_Map::instance();
}