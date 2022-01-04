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
use spitfire\cli\arguments\Parser;

class ParserTest extends TestCase
{
	
	public function testConsume()
	{
		
		$spec = [
			'-v' => '--verbose',
			'--verbose' => [
				'type' => 'bool',
				'description' => 'Sets the verbosity'
			],
			'--user' => [
				'type' => 'string',
				'description' => 'Username'
			]
		];
		
		$parser = new Parser();
		$result = $parser->read($spec, ['-v', '--user', 'root']);
		
		$this->assertEquals(1, $result->getFlag('--verbose'));
		$this->assertEquals(['root'], $result->get('--user'));
	}
}
