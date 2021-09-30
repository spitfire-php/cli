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
	 * @var array<string,string[]>
	 */
	private $options = [];
	
	/**
	 * 
	 * @var array<string, int>
	 */
	private $flags = [];
	
	/**
	 * Operands are the unnamed parameters an application usually receives. This is common in applications
	 * like `cp`, where the first and second operand are the source and target of a copy.
	 * 
	 * @var string[]
	 */
	private $operands = [];
	
	/**
	 * The console arguments usually have one quirk. If they receive an empty option key (--), they will
	 * stop parsing options and assume that everything after this are operands. Since we have several uncorrelated
	 * consumers, having this here allows the consumer that detects this scenario set this flag so we can handle
	 * the behavior accordingly.
	 * 
	 * @var bool
	 */
	private $acceptsOptions = true;
	
	/**
	 * 
	 * @return string[]|null
	 */
	public function get(string $name) :? array
	{
		return isset($this->options[$name])? $this->options[$name] : null;
	}
	
	/**
	 * 
	 * @return string|null
	 */
	public function single(string $name) :? string
	{
		/**
		 * If there is nothing defined for the key, we return null.
		 */
		if (!isset($this->options[$name])) {
			return null;
		}
		
		return reset($this->options[$name])?: null;
	}
	
	/**
	 * @param string $name
	 * @param string[] $value
	 */
	public function set(string $name, array $value) : CLIParameters
	{
		$this->options[$name] = $value;
		return $this;
	}
	
	public function put(string $name, string $value) : CLIParameters
	{
		/**
		 * If the option has no 
		 */
		if (!array_key_exists($name, $this->options)) {
			$this->options[$name] = [];
		}
		
		$this->options[$name][] = $value;
		return $this;
	}
	
	public function getFlag(string $name) :? int
	{
		return $this->flags[$name]?? null;
	}
	
	public function setFlag(string $name, int $value) : CLIParameters
	{
		$this->flags[$name] = $value;
		return $this;
	}
	
	public function increment(string $name) : CLIParameters
	{
		$this->flags[$name] = ($this->flags[$name]?? 0) + 1;
		return $this;
	}
	
	
	public function defined(string $name) : bool
	{
		return array_key_exists($name, $this->options);
	}
	
	public function acceptsOptions() : bool
	{
		return $this->acceptsOptions;
	}
	
	public function setAcceptsOptions(bool $set) : CLIParameters
	{
		$this->acceptsOptions = $set;
		return $this;
	}
	
	public function putOperand(string $operand): CLIParameters
	{
		$this->operands[] = $operand;
		return $this;
	}
	
	public function getOperands(): array
	{
		return $this->operands;
	}
	
}
