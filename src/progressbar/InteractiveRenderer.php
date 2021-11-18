<?php namespace spitfire\cli\progressbar;

use spitfire\cli\Stream;
use spitfire\cli\support\Console;

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
 * The interactive renderer allows the application to do more fancy tricks, adding
 * support for colorized output, stretching to the window, or displaying progress
 * moving backwards.
 * 
 */
class InteractiveRenderer
{

	/**
	 * @var Stream
	 */
	private $stream;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * Contains the timestamp of the last redraw, this prevents the application
	 * from being to aggressive updating the screen by providing a debounce logic.
	 * 
	 * @var int
	 */
	private $lastredraw;
	
	public function __construct(string $message, Stream $stream)
	{
		$this->message = $message;
		$this->stream = $stream;
	}
	
	/**
	 * Generates output that is sent to the stream.
	 * 
	 * @param float $progress A percent value between 0 and 1
	 */
	public function render(float $progress) : void
	{
			
		if (time() === $this->lastredraw) {
			return; 
		}

		$this->lastredraw = time();
		$this->stream->rewind();
		
		$console_width = Console::width();
		$decoration = strlen('[WAIT]  []');

		if ($progress < 0 || $progress > 1) {
			$this->stream->out(sprintf('[WAIT] %s [%s]', $this->message, 'Invalid value ' . $progress));
		}
		
		/**
		 * If the console is wide enough to actually print anything of value. We will do that.
		 * 
		 * Otherwise, the system may cause issues, rendering garbage and flooding the user's screen
		 * with nonsense.
		 */
		elseif ($console_width > strlen($this->message) + $decoration) {
			$width = Console::width() - strlen($this->message) - $decoration;
			$drawn = (int)($progress * $width);
			$gfx   = str_repeat('#', $drawn) . str_repeat(' ', $width - $drawn);
			$msg   = sprintf('[WAIT] %s [%s]', $this->message, $gfx);
			$this->stream->out($msg);
		}
		
		/**
		 * If the console happens to be too narrow to fit our output, we will revert to printing
		 * a message as small as possible sothe user knows that the application is working in the
		 * background.
		 */
		else {
			$this->stream->out(sprintf('[WAIT] %s', ['/', '-', '\\', '|'][time() % 4]));
		}
	}
}
