<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @uses Kohana_Date
 */
class Dc_Date
{
	public static function fuzzy_span($timestamp)
	{
		// Determine the difference in seconds
		$offset = abs(time() - $timestamp);
		$span = '';
		
		if ($offset < Kohana_Date::MINUTE)
		{
			$span = "$offset seconds ago";
		}
		elseif ($offset <= (Kohana_Date::MINUTE + 59))
		{
			$span = "a minute ago";
		}
		elseif ($offset < Kohana_Date::HOUR)
		{
			$span = floor($offset / Kohana_Date::MINUTE) . ' minutes ago';
		}
		elseif ($offset <= (Kohana_Date::HOUR * 2) - 1)
		{
			$span = "an hour ago";
		}
		elseif ($offset < Kohana_Date::DAY)
		{
			$span = floor($offset / Kohana_Date::HOUR) . ' hours ago';
		}
		elseif ($offset <= (Kohana_Date::DAY * 2) - 1)
		{
			$span = "Yesterday";
		}
		elseif ($offset < Kohana_Date::WEEK)
		{
			$span = "last " . date('l', $timestamp);
		}
		elseif ($offset <= (Kohana_Date::WEEK + (Kohana_Date::HOUR * 5)))
		{
			$span = "a week ago";
		}
		elseif ($offset < (Kohana_Date::MONTH * 3))
		{
			$span = date('M j', $timestamp);
		}
		else
		{
			$span = date('M j, Y', $timestamp);
		}
		return $span;
	}
}

