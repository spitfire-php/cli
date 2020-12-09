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
 * A pipe renderer is used whenever the output of a command is being redirected
 * to a non interactive stream, usually a file or another process.
 * 
 * Unlike the interactive renderer, this one does not make use of any rewind calls,
 * nor does it style the interface.
 * 
 */
class PipeRenderer
{

	/**
	 * @var Stream
	 */
	private $stream;
	
	/**
	 * This is a message that gets prepended to the progress bar itself, indicating
	 * to the user what the application is doing.
	 * 
	 * @var string
	 */
	private $message;
	
	private $renderedMessage = false;
	private $progress = 0;
	
	public function __construct ($message, $stream)
	{
		$this->message = $message;
		$this->stream = $stream;
	}
	
	public function render($progress) {
		
		$percent = (int)($progress * 100);
		if ($percent <= $this->progress) { return; }
		
		if (!$this->renderedMessage) {
		$this->stream->out(sprintf('[WAIT] %s ', $this->message));
		$this->renderedMessage = true;
		}
		
		if ($progress < 0 || $progress > 1) {
		#Do nothing. This cannot be rendered.
		}
		else {
		$this->stream->out(str_repeat('.', $percent - $this->progress));
		$this->progress = $percent;
		}
	}
}