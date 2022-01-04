<?php namespace spitfire\cli\tests\arguments;

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

use PHPUnit\Framework\TestCase;
use spitfire\cli\arguments\ArgumentBuffer;

class ArgumentBufferTest extends TestCase
{
	
	public function testConsume()
	{
		$buffer = new ArgumentBuffer(['hello', 'world']);
		$this->assertEquals('hello', $buffer->read());
		$this->assertEquals('world', $buffer->read());
		$this->assertEquals(true, $buffer->exhausted());
	}
}
