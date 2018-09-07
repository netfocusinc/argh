<?php
	
namespace NetFocus\Argh;

class ArghArgumentParser
{
	
	public static function parse(array $args, array $rules, array $parameters)
	{
		// parse $args using $rules and $parameters to create an $arguments array
		
		if( count($rules) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Needs at least one rule to parse arguments.');
		}
		
		if( count($parameters) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Needs at least one parameter to parse arguments.');
		}
		
		// Initialize an array of arguments, this will be returned later
		$arguments = array();
		
		// As parsing progresses, args will be divided into 2-sides (Left-and-Right)
		// The Left-Hand-Side will contain args to attempt matching with rules
		// The Right-Hand-Side will save args that didn't match in previous iterations (to be checked again later)
		
		do
		{
			// Reset temporary variables
			$argsL = array();
			$argsR = array();
			$argsS = "";
			
			// Copy remaining $args to $argsL (left-hand-side array)
			$argsL = array_merge($args);
			
			do
			{
				// Combine $argsL elements into a single string, for matching against rules
				$argsS = implode(' ', $argsL);
				
				//
				// DEBUG: show detailed contents of each variable
				//
				
				echo "\nITERATION:\n----------------------\n";
				 
				echo implode(' ', $argsL) . " | " . implode(' ', $argsR) . "\n";
				
				for($i=0; $i<count($argsL); $i++)
				{
					for($j=0; $j<strlen($argsL[$i]); $j++)
					{
						echo $i;
					}
					echo " ";
				}
				echo "| ";
				for($i=0; $i<count($argsR); $i++)
				{
					for($j=0; $j<strlen($argsR[$i]); $j++)
					{
						echo $i;
					}
					echo " ";
				}			
				echo "\n----------------------\n\n";
				
				echo "\nDEBUG: Considering: " . $argsS . " ... \n\n";
				
				foreach($rules as $rule)
				{
					echo "DEBUG: Checking for match with rule: " . $rule['name'] . " (" . $rule['syntax'] . ")" . "\n";
					
					$tokens = array(); // init array to capture matching tokens from preg_match()
					
					if( preg_match($rule['syntax'], $argsS, $tokens) )
					{
						// Count the number of arguments that were matched
						$count = count($argsL);
						
						echo "* MATCH (n=$count) *\n";
						
						// Empty $argsL; prevent this inner while loop from continuing
						for($i=0; $i<$count; $i++) array_shift($argsL);
						
						// Remove (shift) matching elements from $args
						// These arguments have been consumed by the parser and are no longer needed
						for($i=0; $i<$count; $i++) array_shift($args);
						
						// Build an argument in a $tmp array
						//$tmp = array();
						
						break; // stop checking rules
						
					} // END: if( preg_match($rule->syntax, $args[$i], $matches) )
					
				} // END: foreach($rules as $rule)
				
				if( count($tokens) == 0 )
				{
					// $argsS did NOT match any rules
					
					// Pop last element off of $argsL
					$arg = array_pop($argsL);
					
					// Prepend popped elemented to beginning of $argsR
					array_unshift($argsR, $arg);
					
					if( count($argsL) == 0 )
					{
						// There was no match, and there are no arguments left to pop from $argsL
						throw new ArghException(__METHOD__ . ': Syntax Error: ' . $arg);
					}
					
				} // END: if( count($tokens) == 0 )
				
			} // END do
			while( count($argsL) > 0 );
			
		} // END: do
		while( count($args) > 0 );
		
		return $arguments;
		
	} // END: public static function parse()
	
}