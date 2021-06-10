<?php namespace spitfire\cli\arguments;

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
 * The argument buffer is a very simple abstraction on an array to 
 * quickly loop over the items in it by allowing multiple mechanisms
 * to consume from it.
 */
class ArgumentBuffer
{
	/**
	 * The items that the buffer holds. We use the fact that PHP keeps
	 * an internal array pointer to maintain the position to leverage
	 * the built in functions.
	 * 
	 * @var string[]
	 */
	private $items;
	
	/**
	 * Initialize the buffer to let the different components of the parser
	 * slowly consume the entries.
	 * 
	 * @param string[] $items
	 */
	public function __construct(array $items)
	{
		$this->items = $items;
	}
	
	/**
	 * Retrieve the next item from the buffer. Please note that this consumes
	 * the previous item, making it impossible to retrieve.
	 * 
	 * I've renamed this from next since next implies that we're reading the 
	 * next item (just like the next function in PHP does), but this is not the
	 * case, the buffer will return the current item and forward the pointer.
	 * 
	 * @return string
	 */
	public function read() : string
	{
		$_ret = current($this->items);
		next($this->items);
		return $_ret;
	}
	
	
	/**
	 * Advances the array pointer, consuming the item. This is equivalent to the
	 * `next` method, but allows for method chaining.
	 * 
	 * @return ArgumentBuffer
	 */
	public function forward() : ArgumentBuffer
	{
		next($this->items);
		return $this;
	}
	
	/**
	 * Returns the current item from the buffer, this does not advance the array pointer.
	 * 
	 * @return string
	 */
	public function peek() : string
	{
		return current($this->items);
	}
	
	/**
	 * Returns true if the buffer contains no more items for us to read.
	 * 
	 * @return bool
	 */
	public function exhausted() : bool
	{
		return key($this->items) === null;
	}
	
	/**
	 * Returns all the items the buffer holds. This includes consumed items.
	 * 
	 * @return string[]
	 */
	public function all() : array
	{
		return $this->items;
	}
	
}
 