<?php namespace spitfire\cli\arguments;

use Exception;

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

class CLIParameters
{
	
	/**
	 * 
	 * @var array<string,string>
	 */
	private $params;
	
	/**
	 * 
	 * @param array<string,string> $params
	 */
	public function __construct(array $params) 
	{
		$this->params = $params;
	}
	
	public function redirect(string $from, string $to) : void
	{
		
		if (isset($this->params[$from]) && !isset($this->params[$to])) {
			$this->params[$to] = $this->params[$from];
			unset($this->params[$from]);
		}
		elseif (isset($this->params[$from]) && !isset($this->params[$to])) {
			throw new Exception('Redirection collission', 1805291301);
		}
	}
	
	public function get(string $name) :? string
	{
		return isset($this->params[$name])? $this->params[$name] : null;
	}
	
	
	public function defined(string $name) : bool
	{
		return array_key_exists($name, $this->params);
	}
	
}