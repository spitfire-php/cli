<?php namespace spitfire\cli;

use spitfire\cli\progressbar\InteractiveRenderer;
use spitfire\cli\progressbar\PipeRenderer;

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
 * A progress bar is useful to render a visual indication of the status of a
 * long running task, without making the output too verbose.
 * 
 * Progress bars behave drastically different depending on whether they have a 
 * TTY available or not. Whenever a TTY is available, the progress bar will be 
 * able to render progress bars that can undo their progress (for example due
 * to a queue becoming longer), they can stretch to the full width of the 
 * terminal and they can display a success message once done.
 * 
 * On the other hand, these features cause log files to explode in size, since
 * they cause the system to append all the control characters, making the file
 * enormous and unnecessarily complicated. 
 */
class ProgressBar
{
	
	/**
	 * The stream is the target of our output.
	 * 
	 * @var Stream
	 */
	private $stream;
	
	/**
	 * Depending on the context of the application, the progress
	 * bar will select a renderer that can handle the stream and 
	 * present the best available output.
	 * 
	 * @todo These could have an overarching interface
	 * @var InteractiveRenderer|PipeRenderer
	 */
	private $renderer;
	
	/**
	 * Instances a new progress bar.
	 * 
	 * @param string $msg
	 */
	public function __construct($msg)
	{
		$this->stream = new Stream();
		
		$this->renderer = $this->stream->isInteractive()? 
			new InteractiveRenderer($msg, $this->stream) : 
			new PipeRenderer($msg, $this->stream);
		
		$this->renderer->render(0);
	}
	
	/**
	 * Update the progress bar.
	 * 
	 * @param float $progress A percent value between 0 and 1
	 * @return ProgressBar
	 */
	public function progress($progress)
	{
		$this->renderer->render($progress);
		return $this;
	}
	
	/**
	 * Add a blank line. This method is usually invoked for convenience at the
	 * end of a progress bar to prevent the next output from being appended to 
	 * the same line.
	 * 
	 * @return ProgressBar
	 */
	public function line()
	{
		$this->stream->line();
		return $this;
	}
}
