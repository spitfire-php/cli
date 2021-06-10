<?php namespace spitfire\cli\arguments\consumer;

use \spitfire\cli\arguments\ArgumentBuffer;
use \spitfire\cli\arguments\CLIParameters;

/* 
 * The MIT License
 *
 * Copyright 2021 César de la Cal Bretschneider <cesar@magic3w.com>.
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
 * The operand consumer extracts operands from the arguments list. Since this
 * has a very low priority compared to Option consumers (it will be the last
 * consumer), it will just feed all the items from the Buffer diretly into the
 * Parameters object.
 */
class OperandConsumer implements ConsumerInterface
{
	
	public function consume(ArgumentBuffer $argument, CLIParameters $into) 
	{
		/**
		 * The operand consumer is greedy. It will take anything it receives and 
		 * just place it inside the operands list.
		 */
		$into->putOperand($argument->next());
		return true;
	}

}
