<?php namespace spitfire\cli\tests\filters;

use PHPUnit\Framework\TestCase;
use spitfire\cli\arguments\filters\RedirectionFilter;

class RedirectionFilterTest extends TestCase
{
	
	public function testBasic()
	{
		$args = [
			'index.php',
			'-a',
			'--hello',
			'world'
		];
		
		$spec = [
			'-a' => '--append',
			'-v' => '--verbose',
			'--verbose' => [
				'type' => 'bool',
				'description' => 'Provide verbose output'
			]
		];
		
		$filter = new RedirectionFilter();
		$result = $filter->filter($spec, $args);
		
		$this->assertEquals('--append', $result[1]);
	}
}
