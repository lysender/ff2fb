<?php defined('SYSPATH') or die('No direct script access.');

class Paginate
{
	public static $max;
	
	public static $per_page = 20;
	
	public static $per_nav = 20;
	
	public static $page_prefix = '/index/';
	
	public static $prev_link = '&nbsp;&nbsp;<&nbsp;&nbsp;';
	
	public static $next_link = '&nbsp;&nbsp;>&nbsp;&nbsp;';
	
	/**
	 * Renders a pagination links. Only renders the nearest {$page_page}
	 * links to the current page
	 *
	 * @param int $max			Maximum items
	 * @param int $per_page		Items per page
	 * @param int $page			Current page
	 * @return string
	 */
	public static function render($max, $per_page, $page = null)
	{
		if (!$max)
		{
			return null;
		}
		
		// get total pages
		$s = '';
		$page = (int)$page;
		$total_pages = ceil($max / $per_page);
		
		// identify the current page
		if ($page < 1)
		{
			$page = 1;
		}
		else if ($page > $total_pages)
		{
			$page = $total_pages;
		}
		
		// identify the start and end page links to display
		$start = null;
		if ($page <= self::$per_nav)
		{
			$start = 1;
		}
		else
		{
			$start = (floor($page / self::$per_nav) * self::$per_nav) + 1;
		}
		
		$end = $start + (self::$per_nav - 1);
		if ($end > $total_pages)
		{
			$end = $total_pages;
		}
		
		$s .= '<p class="paginator">';
		
		// show the first page when it is not visible from the nav
		if ($start > self::$per_nav)
		{
			$first = HTML::anchor(
				URL::site('/', true),
				'&nbsp;1&nbsp;'
			);
			$s .= $first . ' ... ';
		}
		
		// add a link to previous page only when we are not on the first page
		if ($page > 1)
		{
			$prev = HTML::anchor(
				URL::site(self::$page_prefix . ($page - 1), true),
				self::$prev_link
			);
			$s .= $prev;
		}
		
		// add up to {self::$per_nav} pages
		for ($x = $start; $x <= $end; $x++)
		{
			// apply current page if possible
			$class = '';
			if ($x == $page)
			{
				// no link for current
				$s .= '[ ' . $x . ' ]';
			}
			else
			{
				$url = null;
				if ($x == 1)
				{
					$url = URL::site('/', true);
				}
				else
				{
					$url = URL::site(self::$page_prefix . $x, true);
				}
				
				$s .= HTML::anchor($url, '&nbsp;&nbsp;' . $x . '&nbsp;&nbsp;');
			}
		}
		
		// add a link to next page only when we are not on the last page
		if ($page < $total_pages)
		{
			$next = HTML::anchor(
				URL::site(self::$page_prefix . ($page + 1), true),
				self::$next_link
			);
			$s .= $next;
		}
		
		// add link to last oage only when it is not visible
		if ($page < $total_pages && ($start + self::$per_nav) < $total_pages)
		{
			$last = HTML::anchor(
				URL::site(self::$page_prefix . $total_pages, true),
				'&nbsp;' . $total_pages . '&nbsp;&nbsp;'
			);
			
			$s .= ' ... ' . $last;
		}
		
		// end tag
		$s .= '</p>';
		return $s;
	}
}