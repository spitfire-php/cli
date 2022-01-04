<?php namespace spitfire\cli\tests\consumer;

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
use spitfire\cli\arguments\CLIParameters;
use spitfire\cli\arguments\consumer\OptionConsumer;

class OptionConsumerTest extends TestCase
{
	
	public function testConsume()
	{
		$schema = [
			'key' => '--name',
			'description' => 'Sets the name of the script',
			'multiple' => true
		];
		
		$buffer = new ArgumentBuffer(['--name', 'test', '--name', 'more']);
		$params = new CLIParameters();
		
		$consumer = new OptionConsumer($schema);
		while ($consumer->consume($buffer, $params));
		
		$this->assertEquals(['test', 'more'], $params->get('--name'));
	}
	
	public function testConsumeSingle()
	{
		$schema = [
			'key' => '--name',
			'description' => 'Sets the name of the script',
			'multiple' => false
		];
		
		$buffer = new ArgumentBuffer(['--name', 'test', '--name', 'more']);
		$params = new CLIParameters();
		
		$consumer = new OptionConsumer($schema);
		while ($consumer->consume($buffer, $params));
		
		$this->assertEquals(['more'], $params->get('--name'));
	}
	
	public function testConsumeShort()
	{
		$schema = [
			'key' => '-u',
			'description' => 'Sets the username',
			'multiple' => false
		];
		
		$consumer = new OptionConsumer($schema);
		$buffer = new ArgumentBuffer(['-u', 'root']);
		$params = new CLIParameters();
		
		while ($consumer->consume($buffer, $params));
		
		$this->assertEquals(['root'], $params->get('-u'));
	}
	
	
	public function testConsumeMultiple()
	{
		$schema = [
			'key' => '-u',
			'description' => 'Sets the username',
			'multiple' => true
		];
		
		$consumer = new OptionConsumer($schema);
		$buffer = new ArgumentBuffer(['-u', 'root', '-u', 'system']);
		$params = new CLIParameters();
		
		while ($consumer->consume($buffer, $params));
		
		$this->assertEquals(['root', 'system'], $params->get('-u'));
	}
	
	/**
	 * If the consumer accepts multiple arguments, even if there is only one entry provided,
	 * the system should still return an array
	 */
	public function testConsumeSingleIntoMultiple()
	{
		$schema = [
			'key' => '-u',
			'description' => 'Sets the username',
			'multiple' => true
		];
		
		$consumer = new OptionConsumer($schema);
		$buffer = new ArgumentBuffer(['-u', 'root']);
		$params = new CLIParameters();
		
		$consumer->consume($buffer, $params);
		
		$this->assertEquals(['root'], $params->get('-u'));
	}
}
