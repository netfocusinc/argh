<?php
	
namespace NetFocus\Argh;

class ArgumentParser
{
	
	public static function parse(array $args, Language $language, Parameters $parameters)
	{
		// Parse $args using $rules and $params to create Arguments
		$rules = $language->rules();
		$params = $parameters->all();
		
		if( count($rules) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Language needs at least one rule to parse arguments.');
		}
		
		if( count($params) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Needs at least one parameter to parse arguments.');
		}
		
		// Create a new Arguments instance
		$arguments = new Arguments();
		
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
					echo "DEBUG: Checking for match with rule: " . $rule->name() . " (" . $rule->syntax() . ")" . "\n";
					
					$tokens = array(); // init array to capture matching tokens from preg_match()
					
					if( preg_match($rule->syntax(), $argsS, $tokens) )
					{
						// Count the number of arguments that were matched
						$count = count($argsL);
						
						echo "* MATCH (n=$count) *\n";
						
						// Empty $argsL; prevent this inner while loop from continuing
						for($i=0; $i<$count; $i++) array_shift($argsL);
						
						// Remove (shift) matching elements from $args
						// These arguments have been consumed by the parser and are no longer needed
						for($i=0; $i<$count; $i++) array_shift($args);
						
						// Create a new Argument instance
						//$argument = array();

	 					// Loop through $tokens and assign data to $arguments based on the current rules semantics
	 					for($j=1; $j<count($tokens); $j++)
	 					{
	 						$token = $tokens[$j];

	 						echo "DEBUG: token: " . $token . " (" . $rule->semantics()[$j-1] . ")\n";
	 						
	 						switch( $rule->semantics()[$j-1] )
	 						{
	 							case ARGH_SYM_KEY:
	 							
	 								// Check if this 'key' matches a defined parameters 'name' or 'flag'	 								
	 								for($k=0; $k<count($params); $k++)
	 								{
		 								if( ($token == $params[$k]->name) || ($token == $params[$k]->flag) )
		 								{
			 								// Use the parameters 'name' for this arguments 'key'
			 								//$argument['key'] = $params[$k]->name;
			 								
			 								// Stop searching for parameter
			 								break;
			 							}
		 							}
		 							
		 							// Token does not match any defined parameter
		 							//if( !array_key_exists('key', $argument) )
		 							//{
			 							//throw new ArghException(__METHOD__ . ': No parameter with key: ' . $token . "'");
			 						//}
	 								
	 								break;
	 								
	 							case ARGH_SYM_KEYS:
	 							
	 								//! NOTES: this should/will add multiple new arguments
	 								
	 								//! TODO: Seperate keys (into single character flags), check that each matches a defined parameter
	 								// NOTE: KEYS are ALWAYS for boolean parameters
	 								
	 								for($k=0; $k<strlen($token); $k++)
	 								{
		 								
		 								for($m=0; $m<count($params); $m++)
		 								{
			 								if( $token{$k} == $params[$m]->flag )
			 								{
				 							}
			 							}
		 								
		 							}
	 								
	 								break;
	 								
	 								
	 							case ARGH_SYM_VALUE:
	 							
	 								//$argument['value'] = $token;
	 								
	 								break;
	 								
	 							case ARGH_SYM_LIST:
	 							
	 								break;
	 								
	 							case ARGH_SYM_CMD:
	 							
	 								break;
	 								
	 							case ARGH_SYM_SUB:
	 							
	 								break;
	 								
	 							default:
	 							
	 								throw new ArghException(__METHOD__ . ': Token has unknown semantic meaning.');
	 						}
	 						
	 					} // END: for($j=1; $j<count($matches); $j++)
	 					
	 					//! TODO: set boolean values for flags to TRUE
	 					
	 					//! TODO: validate tmp argument before adding to this objects arguments array
	 					
	 					// Add $argument to Arguments
	 					//$arguments->addArgument($argument);

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