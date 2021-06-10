<?php namespace spitfire\cli\arguments\consumer;

use \spitfire\cli\arguments\ArgumentBuffer;
use \spitfire\cli\arguments\CLIParameters;

/* 
 * The MIT License
 *
 * Copyright 2018 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
		if (!$into->acceptsOptions()) { return false; }
		
		/**
		 * If the next entry starts with a hyphen (-), the consumer should continue,
		 * but if the next item does start with something else we jump, and if the
		 * item starts with two dashes, we skip it too.
		 * 
		 * If the key is only one dash, we will also stop it right there, since it
		 */
		if ($key !== $this->schema['key']) { return false; }
		
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
			$into->put($key, $argument->next());
		}
		/**
		 * Otherwise we will override the value of the key. This means that if a user submits the
		 * same argument twice, the application will use the last.
		 */
		else {
			$into->set($key, $argument->next());
		}
		
		return true;
	}

}
