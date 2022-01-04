<?php namespace spitfire\cli\arguments\filters;

class EqualsFilter implements FilterInterface
{
	
	
	/**
	 * 
	 * @param string[] $input
	 * @return string[]
	 */
	public function filter(array $spec, array $input) : array
	{
		
		$_ret = [];
		
		/**
		 * We need to loop over the individual components to look for elements
		 * that contain a equals. This is a bit of a quirk of the CLI (at least in 
		 * linux machines) where the input some="value" will be passed into PHP
		 * as the string some=value, even if the value actually contains spaces.
		 * 
		 * Linux will strip the quotes, so it's virtually impossible to detect 
		 * whether the user entered the string as a 
		 */
		while ($piece = array_shift($input)) {
			
			/**
			 * If we reached the end of the arguments (and therefore are only expecting
			 * operands to be passed) we can stop checking.
			 * 
			 * We just return the stuff we have, and the end of the operators.
			 */
			if ($piece === '--') {
				return array_merge($_ret, $input);
			}
			
			/**
			 * If the string is not an option, we can safely ignore it
			 */
			if ($piece[0] !== '-') {
				$_ret[] = $piece;
				continue;
			}
			
			/**
			 * If the expression looks like a --something=value type of expression,
			 * we run with it.
			 */
			if (preg_match('/\-\-?[A-Za-z0-9_\-]+\=/', $piece, $matches)) {
				$_ret = array_merge($_ret, explode('=', $piece, 2));
			}
			else {
				$_ret[] = $piece;
			}
		}
		
		return $_ret;
	}
}
