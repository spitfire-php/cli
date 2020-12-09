<?php namespace spitfire\cli\progressbar;

use spitfire\core\Collection;
use spitfire\cli\Stream;

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
	
	public function __construct ($message, $stream)
	{
		$this->message = $message;
		$this->stream = $stream;
	}
	
	/**
	 * Generates output that is sent to the stream.
	 * 
	 * @param int $progress A percent value between 0 and 1
	 */
	public function render($progress) {
			
		if (time() === $this->lastredraw) { return; }

		$this->lastredraw = time();
		$this->stream->rewind();

		if ($progress < 0 || $progress > 1) {
			$this->stream->out(sprintf('[WAIT] %s [%s]', $this->message, 'Invalid value ' . $progress));
		}
		else {
			$width = exec('tput cols') - strlen($this->message) - 10;
			$drawn = (int)($progress * $width);
			$this->stream->out(sprintf('[WAIT] %s [%s%s]', $this->message, str_repeat('#', $drawn), str_repeat(' ', $width - $drawn)));
		}
	}
}