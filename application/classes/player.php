<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Player helper class
 */
class Player
{
	const BUTTON_WIDTH = 29;
	const BUTTON_HEIGHT = 29;
	
	/**
	 * Returns an array of w x h based on the button dimension
	 * agains the image dimension. This is used to position the play and stop
	 * button for video player
	 *
	 * @param int $img_w
	 * @param int $img_h
	 * @return array
	 */
	public static function button_position($img_w, $img_h)
	{
		// get the horizontal position
		// img_w / 2 minus button_w / 2
		$left = ($img_w / 2) - (self::BUTTON_WIDTH / 2);
		
		// get the vertical position
		// img_h / 2 minus button_h / 2
		$top = ($img_h / 2) - (self::BUTTON_HEIGHT / 2);
		
		return array(
			'left'	=> (int)$left,
			'top'	=> (int)$top
		);
	}
}