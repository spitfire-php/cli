<?php namespace spitfire\cli\arguments;

use spitfire\cli\arguments\consumer\ConsumerInterface;
use spitfire\cli\arguments\consumer\EndOfOptionsConsumer;
use spitfire\cli\arguments\consumer\FlagConsumer;
use spitfire\cli\arguments\consumer\LongParamExtractor;
use spitfire\cli\arguments\consumer\OperandConsumer;
use spitfire\cli\arguments\consumer\OptionConsumer;
use spitfire\cli\arguments\consumer\ShortParamExtractor;
use spitfire\cli\arguments\consumer\STDINConsumer;
use spitfire\cli\arguments\consumer\STDINExtractor;
use spitfire\cli\arguments\consumer\StopCommandExtractor;
use spitfire\cli\arguments\filters\EqualsFilter;
use spitfire\cli\arguments\filters\FilterInterface;
use spitfire\cli\arguments\filters\RedirectionFilter;
use spitfire\collection\Collection;

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
 * This parser receives the output of a parameters() function from a director and 
 * uses it to extract the user's options and arguments accordingly to the specification
 * of the director.
 * 
 * The parser then will expose a read() method that receives the argv and generates
 * an array with configuration according to the spec.
 */
class Parser
{
	
	/**
	 * Create a new argument parser for the command. To instance this we need an array
	 * containing the specification explaining how options and arguments are parsed.
	 * 
	 * Each of the keys can be formatted like:
	 * 
	 * 1. A single dash followed by a single character (like -v)
	 * 2. Two dashes followed by an arbitrary number of characters (like --working-dir)
	 * 
	 * Each of the values of the spec can contain the following
	 * 
	 * 1. A string (this is a redirection / alias to another argument - something like -v and --verbose)
	 * 2. An array with the following keys
	 *  - `required` (whether the application should fail if the argument is not available)
	 *  - `type` (is an enum of bool, number and string - performs verification, bools are assumed to be flags)
	 *  - `multiple` (optional - allows the user to use this multiple times, if true the value will always be an array)
	 *  - `description` (explains what the argument is good for)
	 * 
	 * The system will automatically extract operands. Operands are all strings that satisfy one of the following
	 * conditions:
	 * 
	 * 1. They appear after a double-dash (--) operator
	 * 2. They are not immediately preceeded by a non-boolean operand and do not start with a hyphen
	 * 
	 * @param mixed[] $spec
	 * @param string[] $argv
	 * @return CLIParameters
	 * @see https://phabricator.magic3w.com/source/spitfire/browse/master/mvc/Director.php For a sample specification
	 */
	public function read(array $spec, array $argv) 
	{
		$filters = new Collection([
			new EqualsFilter(),
			new RedirectionFilter()
		]);
		
		$consumers = new Collection();
		
		foreach ($spec as $key => $schema) {
			/**
			 * The spec can contain arrays for the consumers and strings
			 * for the redirections. We do not care about the redirections here
			 * so we can safely skip them.
			 */
			if (is_string($schema)) {
				continue; 
			}
			
			/**
			 * Inject the key into the schema so the consumer knows what data it
			 * is working with and where it should place the results.
			 */
			$schema['key'] = $schema['key']?? $key;
			
			/**
			 * Create the consumers for the data we are expecting. Please note that
			 * we make a distinction between bools and strings (and numbers) so we 
			 * need to account for that.
			 */
			switch ($schema['type']?? 'string') {
				case 'bool':
					$consumers->push(new FlagConsumer($schema));
					break;
				case 'string':
				case 'number':
					$consumers->push(new OptionConsumer($schema));
					break;
			}
		}
		
		/**
		 * Push the standard consumers that are not related to the director specific options.
		 * This allows our application to read operands and similar stuff.
		 */
		$consumers->push(new EndOfOptionsConsumer());
		$consumers->push(new STDINConsumer());
		$consumers->push(new OperandConsumer());
		
		/**
		 * Filter the input we received. This provides us with normalized input and allows our 
		 * application to read ooptions that contain equals signs or shorthand options.
		 */
		$input  = new ArgumentBuffer($filters->reduce(function (array $carry, FilterInterface $f) use ($spec) {
			return $f->filter($spec, $carry);
		}, $argv));
		
		/**
		 * Prepare an output object that will receive all the data and hold state information on
		 * the read data.
		 */
		$output = new CLIParameters();
		
		/**
		 * Extract the data from the argument buffer.
		 */
		while (!$input->exhausted()) {
			foreach ($consumers as $consumer) {
				if ($consumer->consume($input, $output)) {
					continue 2;
				}
			}
		}
		
		return $output;
	}
}
