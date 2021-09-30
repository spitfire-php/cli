<?php namespace spitfire\cli\arguments\consumer;

use \spitfire\cli\arguments\ArgumentBuffer;
use \spitfire\cli\arguments\CLIParameters;

/* 
 * Copyright (C) 2021 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
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
		
		if ($argument->exhausted()) {
			return false;
		}
		
		/**
		 * The operand consumer is greedy. It will take anything it receives and 
		 * just place it inside the operands list.
		 */
		$into->putOperand($argument->read());
		return true;
	}

}
