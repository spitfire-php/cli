<?php namespace spitfire\cli\arguments\filters;

/**
 * The filter interface allows the application to perform pre-check
 * operations. For example, option-bunching, where the user can aggregate
 * several options together like `tar -xvfz` which combines a bunch of flags.
 * 
 * The filter will just receive the options from the application and 
 * walk over them, returning a canonical / filtered version of the options.
 * 
 * Other options for filters:
 *  - Canonicalizing input written like arg="somevalue"
 *  - Canonicalizing redirections, so stuff like -v gets extended into --verbose
 *  - Canonicalizing short options with the text directly like `mysql -uroot`
 */
interface FilterInterface
{
	
	/**
	 * 
	 * @param mixed[] $spec
	 * @param string[] $input
	 * @return string[]
	 * @see https://phabricator.magic3w.com/source/spitfire/browse/master/mvc/Director.php For a sample specification
	 */
	public function filter(array $spec, array $input) : array;
}
