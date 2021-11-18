<?php namespace spitfire\cli;

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

class Console
{
	
	/**
	 * The STDOUT stream.
	 * 
	 * @var Stream
	 */
	private $stdout;
	
	/**
	 * The STDERR stream. 
	 * 
	 * @var Stream
	 */
	private $stderr;
	
	/**
	 * @var CLIColor
	 */
	private $colors;
	
	/**
	 * The currently active stream.
	 * 
	 * @var Stream
	 */
	private $current;
	
	/**
	 * Instances a new console interface. This allows the application to output data to the command
	 * line, providing utils for printing labels and progress indicators.
	 */
	public function __construct() 
	{
		$this->stdout = $this->current = new Stream(STDOUT);
		$this->stderr = new Stream(STDERR);
		
		$this->colors = new CLIColor();
	}
	
	/**
	 * 
	 * @param string $msg The message to print.
	 * @return Console
	 */
	public function error(string $msg) : Console
	{
		$this->current = $this->stderr;
		$out = str_replace(PHP_EOL, PHP_EOL . '       ', trim($msg));
		
		$this->current
			->out('[')
			->out($this->colors->color(CLIColor::FG_RED))
			->out('FAIL')
			->out($this->colors->color(CLIColor::RESET))
			->out('] ')
			->out($out);
		
		return $this;
	}
	
	
	/**
	 * Prints a message labeled as information
	 * 
	 * @param string $msg The message to print.
	 * @return Console
	 */
	public function info($msg)
	{
		$this->current = $this->stdout;
		$out = str_replace(PHP_EOL, PHP_EOL . '       ', trim($msg));
		
		$this->current
			->out('[')
			->out($this->colors->color(CLIColor::FG_BLUE))
			->out('INFO')
			->out($this->colors->color(CLIColor::RESET))
			->out('] ')
			->out($out);
		
		return $this;
	}
	
	/**
	 * Prints a success message.
	 * 
	 * @param string $msg The message to print.
	 * @return Console
	 */
	public function success($msg)
	{
		$this->current = $this->stdout;
		$out = str_replace(PHP_EOL, PHP_EOL . '       ', trim($msg));
		
		$this->current
			->out('[')
			->out($this->colors->color(CLIColor::FG_GREEN))
			->out(' OK ')
			->out($this->colors->color(CLIColor::RESET))
			->out('] ')
			->out($out);
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param string $msg The message to print.
	 * @return ProgressBar
	 */
	public function progress($msg) 
	{
		return new ProgressBar($msg);
	}
	
	/**
	 * 
	 * @return Stream
	 */
	public function stdout()
	{
		return $this->stdout;
	}
	
	/**
	 * 
	 * @return Stream
	 */
	public function stderr()
	{
		return $this->stderr;
	}
	
	/**
	 * 
	 * @return Console
	 */
	public function rewind()
	{
		$this->current->rewind();
		return $this;
	}
	
	/**
	 * 
	 * @param int $lines
	 * @return Console
	 */
	public function up($lines = 1) 
	{
		$this->current->up($lines);
		return $this;
	}
	
	/**
	 * 
	 * @param int $lines
	 * @return Console
	 */
	public function down(int $lines = 1) 
	{
		$this->current->down($lines);
		return $this;
	}
	
	/**
	 * 
	 * @return Console
	 */
	public function ln() 
	{
		$this->current->line();
		return $this;
	}
}
