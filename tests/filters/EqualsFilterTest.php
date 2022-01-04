<?php namespace spitfire\cli\tests\filters;

use PHPUnit\Framework\TestCase;
use spitfire\cli\arguments\filters\EqualsFilter;

class EqualsFilterTest extends TestCase
{
	
	public function testBasic()
	{
		$input = [
			'test.php',
			'--hello=world',
			'--goodbye=cruel world',
			'potato salad'
		];
		
		$filter = new EqualsFilter();
		$result = $filter->filter([], $input);
		
		$this->assertEquals('--hello', $result[1]);
		$this->assertEquals('world', $result[2]);
		$this->assertEquals('--goodbye', $result[3]);
		$this->assertEquals('cruel world', $result[4]);
		$this->assertEquals('potato salad', $result[5]);
	}
	
	public function testMixed()
	{
		$input = [
			'test.php',
			'--hello=world',
			'--goodbye',
			'cruel world',
			'potato salad'
		];
		
		$filter = new EqualsFilter();
		$result = $filter->filter([], $input);
		
		$this->assertEquals('--hello', $result[1]);
		$this->assertEquals('world', $result[2]);
		$this->assertEquals('--goodbye', $result[3]);
		$this->assertEquals('cruel world', $result[4]);
		$this->assertEquals('potato salad', $result[5]);
	}
}
