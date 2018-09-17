<?php
	
namespace NetFocus\Argh;

class ArgumentParser
{
	
	/**
		* 
		*
		*
	*/
	
	public static function parse(array $args, Language $language, ParameterCollection $parameters): ArgumentCollection
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
		
		// Create a new ArgumentCollection instance
		$arguments = new ArgumentCollection();
		
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
						
						echo "* MATCHED $count \$argv elements *\n";
						
						// Empty $argsL; prevent this inner while loop from continuing
						for($i=0; $i<$count; $i++) array_shift($argsL);
						
						// Remove (shift) matching elements from $args
						// These arguments have been consumed by the parser and are no longer needed
						for($i=0; $i<$count; $i++) array_shift($args);
						
						// Create an array of new Argument
						// In most cases, a single Rule will create a single Arugment
						// Unless the Rule contains an ARGH_SEMANTICS_FLAGS, which creates an Argument for each flag
						$argument = array();

	 					// Loop through $tokens and define Argument(s) based on the current rules semantics
	 					for($i=1; $i<count($tokens); $i++)
	 					{
	 						$token = $tokens[$i];
	 						$semantics = $rule->semantics()[$i-1];

	 						echo "DEBUG: token: $token (" . Rule::semanticsToString($semantics) . ")\n";
	 						
	 						switch( $semantics )
	 						{ 
	 							case ARGH_SEMANTICS_FLAG:
	 							
	 								// Check if this $token matches a defined parameters 'flag' 								
	 								for($j=0; $j<count($params); $j++)
	 								{
		 								if( $token == $params[$j]->flag() )
		 								{
			 								// This Rule will create a single Argument
			 								if(count($argument)==0) $argument[0] = new Argument();
			 								
			 								// Use the parameters 'name' for this argument's 'key'
			 								$argument[0]->key($params[$j]->name());
			 								
			 								// Argument inherits the 'type' of its parameter
			 								$argument[0]->type($params[$j]->type());
			 								
			 								// Stop searching for matching parameter flag
			 								break;
			 							}
		 							}
		 							
		 							// Check for $token that does not match any defined parameter
		 							if( (!array_key_exists(0, $argument)) || (empty($argument[0]->key())) )
		 							{
			 							throw new ArghException(__METHOD__ . ': No parameter with flag: \'' . $token . '\'');
			 						}
	 								
	 								break;
	 								
	 							case ARGH_SEMANTICS_FLAGS:
	 								
	 								// Create new Argument for each flag
	 								for($j=0; $j<strlen($token); $j++)
	 								{
		 								if( !array_key_exists($j, $argument) ) $argument[$j] = new Argument();
		 							}
	 								
	 								// Check every character of this $token for a matching parameter 'flag'
	 								for($j=0; $j<strlen($token); $j++)
	 								{
		 								
		 								for($k=0; $k<count($params); $k++)
		 								{
			 								
			 								if( $token{$j} == $params[$k]->flag() )
			 								{
				 								// Use the parameters 'name' for this argument's 'key'
				 								$argument[$j]->key($params[$k]->name());
				 								
				 								// Argument inherits the 'type' of its parameter
				 								$argument[0]->type($params[$j]->type());
				 								
				 								// Stop searching for matching parameter flag
				 								break;
				 							}
				 							
			 							} // END: for($k=0; $k<count($params); $k++)
			 							
			 							// Check for $token that does not match any defined parameter
			 							if( empty($argument[$j]->key()) )
			 							{
			 								throw new ArghException(__METHOD__ . ': No parameter with flag: ' . $token . "'");
			 							}	
		 								
		 							} // END: for($j=0; $j<strlen($token); $j++)
	 								
	 								break;
	 								
	 							case ARGH_SEMANTICS_NAME:
	 						
	 								// Check if this $token matches a defined parameters 'name'	 								
	 								for($j=0; $j<count($params); $j++)
	 								{
		 								
		 								echo "DEBUG: Checking param[$j]: '" . $params[$j]->name() . "'\n";
		 								
		 								if( $token == $params[$j]->name() )
		 								{
			 								echo "DEBUG: '$token' matches with parameter name '" . $params[$j]->name() . "'\n";
			 								
			 								// This Rule will create a single Argument
			 								if(count($argument)==0) $argument[0] = new Argument();
			 								
			 								// Use the parameters 'name' for this argument's 'key'
			 								$argument[0]->key($params[$j]->name());
			 								
			 								// Argument inherits the 'type' of its parameter
			 								$argument[0]->type($params[$j]->type());
			 								
			 								// Stop searching for matching parameter name
			 								break;
			 							}
		 							}
		 							
		 							// Token does not match any defined parameter
		 							if( (!array_key_exists(0, $argument)) || (empty($argument[0]->key())) )
		 							{
			 							throw new ArghException(__METHOD__ . ': No parameter with name: \'' . $token . '\'');
			 						}
	 						
	 								break;			
	 								
	 							case ARGH_SEMANTICS_VALUE:
	 							
	 								// If no new Argument created by this Rule yet, create one now
			 						if(count($argument)==0) $argument[0] = new Argument();
	 							
	 								// Use this $token as the 'value' for all new Argument created by this Rule
	 								// Usually, this will only apply to a single Argument
	 								// Unless this Rule contains ARGH_SEMANTICS_FLAGS
	 								foreach($argument as $a) $a->value($token);
	 								
	 								break;
	 								
	 							case ARGH_SEMANTICS_LIST:
	 							
	 								break;
	 								
	 							case ARGH_SEMANTICS_CMD:
	 							
	 								break;
	 								
	 							case ARGH_SEMANTICS_SUB:
	 							
	 								break;
	 								
	 							default:
	 							
	 								throw new ArghException(__METHOD__ . ': Token has unknown semantic meaning.');
	 						}
	 						
	 					} // END: for($j=1; $j<count($matches); $j++)
	 					
	 					// Set boolean values
	 					foreach($argument as &$a)
	 					{
		 					if( ARGH_TYPE_BOOLEAN == $a->type() )
		 					{
			 					// null (no value) considered TRUE (this is how flags work)
			 					// PHP 'Falsey' values should be considered to mean FALSE
			 					// Certain character values should be considered to mean FALSE
			 					// Everything else considered TRUE
			 					
			 					if( null === $a->value() )
			 					{
				 					$a->value(TRUE);
				 				}
				 				else if( FALSE == $a->value() )
				 				{
					 				// 'Falsey' (boolean) FALSE, (int) 0, (float 0.0), (string) '0', (string) '', NULL
					 				$a->value(FALSE);
					 			}
			 					else if( in_array($a->value(), array('0', 'false', 'False', 'FALSE', 'off', 'Off', 'OFF')) )
			 					{
				 					$a->value(FALSE);
				 				}
				 				else
				 				{
					 				$a->value(TRUE);
					 			}
			 					
			 				} // END: if( ARGH_TYPE_BOOLEAN == $a->type() )
		 				} // END: foreach($argument as $a)
	 					
	 					//! TODO: validate Argument (for data type) before adding to this objects arguments array
	 					
	 					// Add the new Argument(s) (from array) to Arguments
	 					foreach($argument as $a) $arguments->addArgument($a);

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