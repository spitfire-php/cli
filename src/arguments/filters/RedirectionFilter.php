<?php namespace spitfire\cli\arguments\filters;

use spitfire\collection\Collection;

/* 
 * Copyright (C) 2021 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

/**
 * The redirection filter allows to canonicalize the parameters your application
 * receives. Your application can then accept parameters that have a short and a 
 * long version without making any distinction.
 */
class RedirectionFilter implements FilterInterface
{
	
	/**
	 * This filter will rewrite redirections to canonicalize them. If we established 
	 * an argument redirection like -a => --append, the filter will replace it with 
	 * the proper one.
	 * 
	 * Please make sure that the behavior is not guaranteed to work properly with redirection
	 * chains, if your application performs multiple redirections, please always point to
	 * the most canonical directly.
	 * 
	 * @param mixed[] $spec
	 * @param string[] $input
	 * @return string[]
	 * @see https://phabricator.magic3w.com/source/spitfire/browse/master/mvc/Director.php For a sample specification
	 */
	public function filter(array $spec, array $input) : array
	{
		$_ret = [];
		
		$spec = (new Collection($spec))->filter(function ($e) {
			return is_string($e);
		});
		
		while ($string = array_shift($input)) {
			/**
			 * Walk over the redirections, looking for one that matches the input we received
			 */
			foreach ($spec as $key => $redirection) {
				if ($string == $key) {
					$_ret[] = $redirection;
					
					/**
					 * If we run into the situation that our code found a redirection, we do 
					 * no longer look for more possible redirections and run with this.
					 * 
					 * This is why the continue has a 2 modifier, because we jump to the next
					 * iteration of the while loop.
					 */
					continue 2;
				}
			}
			
			/**
			 * If we run into the end of arguments string, we will no longer process the
			 * arguments.
			 */
			if ($string === '--') {
				return array_merge($_ret, $input);
			}
			
			/**
			 * In the event our input was matched by none of the redirections, we just assume
			 * it's a 
			 */
			$_ret[] = $string;
		}
		
		return $_ret;
	}
}
