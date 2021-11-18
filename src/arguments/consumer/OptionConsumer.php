<?php namespace spitfire\cli\arguments\consumer;

use \spitfire\cli\arguments\ArgumentBuffer;
use \spitfire\cli\arguments\CLIParameters;

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
 * This class expects a argument buffer to be provided and it will check if the 
 * first parameter is actually a short option.
 * 
 * Short options are options that have a hyphen followed by a single character,
 * which is then followed by a space and an arbitrary string containing the payload
 * of the argument.
 * 
 * An argument would be something like `mysql -u root`.
 * 
 * The arguments for a long argument must be prefixed with two dashes (like --working-dir)
 * and must be separated by a space.
 */
class OptionConsumer implements ConsumerInterface
{
	
	/**
	 * 
	 * @var string[]
	 */
	private $schema;
	
	/**
	 * 
	 * @param string[] $schema Contains the excerpt of the configuration that affects this consumer
	 */
	public function __construct(array $schema)
	{
		$this->schema = $schema;
	}
	
	public function consume(ArgumentBuffer $argument, CLIParameters $into) 
	{
		
		$key = $argument->peek();
		
		/**
		 * It's entirely possible, that the result has been locked by the StopOptionConsumer
		 * which has to be respected by this one.
		 */
		if (!$into->acceptsOptions()) {
			return false; 
		}
		
		/**
		 * If the next entry starts with a hyphen (-), the consumer should continue,
		 * but if the next item does start with something else we jump, and if the
		 * item starts with two dashes, we skip it too.
		 * 
		 * If the key is only one dash, we will also stop it right there, since it
		 */
		if ($key !== $this->schema['key']) {
			return false; 
		}
		
		/**
		 * Once we know that the result does accept more options, and that this is, indeed,
		 * an option we advance the buffer. This means that the key is now consumed.
		 */
		$argument->forward();
		
		/**
		 * If the schema accepts multiple values per argument, the application should 
		 * add the value to the arguments.
		 */
		if ($this->schema['multiple']?? false) {
			$into->put($key, $argument->read());
		}
		/**
		 * Otherwise we will override the value of the key. This means that if a user submits the
		 * same argument twice, the application will use the last.
		 */
		else {
			$into->set($key, [$argument->read()]);
		}
		
		return true;
	}
}
