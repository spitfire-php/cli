<?php namespace spitfire\cli\support;

/**
 * This support class contains a series of methods that we want to give a name to
 * and that should help making working with the console easier.
 */
class Console
{
	
	public static function width()
	{
		return (int)exec('tput cols');
	}
}