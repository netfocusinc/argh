<?php
	
namespace NetFocus\Argh;

/**
	* Summary.
	*
	* Description
	*
	* @internal
	*
	*/
class ArgumentParser
{
	
	/**
		* Summary.
		*
		* Parse $args using $rules and $params to create Arguments
		*
		* @param
		* @param
		*
		* @return
		* @throws
		*
		*/
	public static function parse(array $args, Language $language, ParameterCollection &$parameters): ArgumentCollection
	{
		// Create a new ArgumentCollection instance
		$arguments = new ArgumentCollection();
		
		if(count($args) == 0)
		{
			// Nothing to parse
			return $arguments;
		}
		
		// Get all Rules from Langugage
		$rules = $language->rules();
		
		// Get all Parameters from ParameterCollection
		$params = $parameters->all();
		
		if( count($rules) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Language needs at least one rule to parse arguments.');
		}
		
		if( count($params) == 0 )
		{
			throw new ArghException(__METHOD__ . ': Needs at least one parameter to parse arguments.');
		}
		
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
				//echo "\nITERATION:\n----------------------\n";
				//echo implode(' ', $argsL) . " | " . implode(' ', $argsR) . "\n";
				// END DEBUG
				
				for($i=0; $i<count($argsL); $i++)
				{
					for($j=0; $j<strlen($argsL[$i]); $j++)
					{
						//echo $i;
					}
					//echo " ";
				}
				//echo "| ";
				for($i=0; $i<count($argsR); $i++)
				{
					for($j=0; $j<strlen($argsR[$i]); $j++)
					{
						echo $i;
					}
					//echo " ";
				}			
				//echo "\n----------------------\n\n";
				
				//echo "\nDEBUG: Considering: " . $argsS . " ... \n\n";
				
				foreach($rules as $rule)
				{
					echo "DEBUG: Checking for match with rule: " . $rule->name() . " (" . $rule->syntax() . ")" . "\n";
					
					$tokens = array(); // init array to capture matching tokens from preg_match()
					
					if( preg_match($rule->syntax(), $argsS, $tokens) )
					{
						// Count the number of arguments that were matched
						$count = count($argsL);
						
						//echo "* MATCHED $count \$argv elements *\n";
						
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

	 						//echo "DEBUG: token: $token (" . Rule::semanticsToString($semantics) . ")\n";
	 						
	 						//! TODO: Use methods of ParameterCollection (e.g. exists() and get()) to simplify code below
	 						
	 						switch( $semantics )
	 						{ 
	 							case ARGH_SEMANTICS_FLAG:
	 							
	 								if( $parameters->exists($token) )
	 								{
		 								// Retrieve matching parameter
		 								$p = $parameters->get($token);
		 								
			 							// This Rule will create a single Argument
			 							if(count($argument)==0) $argument[0] = new Argument();
			 								
			 							// Use the parameters 'name' for this argument's 'key'
			 							$argument[0]->key($p->name());
			 								
			 							// Argument inherits the 'type' of its parameter
			 							$argument[0]->type($p->type());		 								
		 							}
		 							else
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
		 							
		 								if( $parameters->exists( $token{$j} ) )
		 								{
			 								// Retrieve matching parameter
			 								$p = $parameters->get($token{$j});
				 								
				 							// Use the parameters 'name' for this argument's 'key'
				 							$argument[$j]->key($p->name());
				 								
				 							// Argument inherits the 'type' of its parameter
				 							$argument[$j]->type($p->type());		
			 							}
			 							else
			 							{
				 							throw new ArghException(__METHOD__ . ': No parameter with flag: ' . $token . "'");
				 						}		
		 								
		 							} // END: for($j=0; $j<strlen($token); $j++)
	 								
	 								break;
	 								
	 							case ARGH_SEMANTICS_NAME:
	 						
	 								if( $parameters->exists($token) )
	 								{
		 								// Retrieve matching parameter
		 								$p = $parameters->get($token);
		 								
			 							// This Rule will create a single Argument
			 							if(count($argument)==0) $argument[0] = new Argument();
			 								
			 							// Use the parameters 'name' for this argument's 'key'
			 							$argument[0]->key($p->name());
			 								
			 							// Argument inherits the 'type' of its parameter
			 							$argument[0]->type($p->type());		 								
		 							}
		 							else
		 							{
			 							throw new ArghException(__METHOD__ . ': No parameter with flag: \'' . $token . '\'');
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
	 							
	 								// Trim brackets from the $token (list)
	 								$token = trim($token, "[]");
	 								
	 								// Explode comma seperated list into elements
	 								$elements = explode(',', $token);
	 								
	 								// Use the $elements array as the 'value' for all new Argument created by this Rule
	 								// Usually, this will only apply to a single Argument
	 								// Unless this Rule contains ARGH_SEMANTICS_FLAGS
	 								foreach($argument as &$a) $a->value($elements);
	 							
	 								break;
	 								
	 							case ARGH_SEMANTICS_COMMAND:
	 							
	 								// Check if ParameterCollection contains any commands
	 								if($parameters->hasCommand())
	 								{
		 								// Retrieve all ARGH_TYPE_COMMAND Parameters
		 								$commands = $parameters->getCommands();
		 								
					 					// If no new Argument created by this Rule yet, create one now
					 					if(count($argument)==0) $argument[0] = new Argument();
					 					
					 					// Argument is assigned ARGH_TYPE_COMMAND type
					 					$argument[0]->type(Parameter::ARGH_TYPE_COMMAND);
		 								
		 								foreach($commands as $p)
		 								{
			 								if($p->hasOptions())
			 								{
				 								if( in_array($token, $p->options()) )
				 								{
					 								// $token matches an option for this command parameter	
					 								
					 								// Use the parameters 'name' for this argument's 'key'
					 								$argument[0]->key($p->name());
					 								
					 								// Use the $token for the Arguments value
					 								$argument[0]->value($token);	
					 							}
				 							}
				 							else
				 							{
					 							throw new ArghException(__METHOD__ . ': Command parameter missing required: \'options\'');
					 						}
			 							} // END: foreach($commands as $p)
			 							
			 							if( empty($argument[0]->value()) )
			 							{
				 							// No Commands with $token as an option
				 							// This Rule can't be used to process this $token
				 							//echo "!!! Not a command token, try processing as a naked variable\n";
				 							
				 							// Continue searching next Rule
				 							unset($argument[0]);
				 							break;
				 						}
				 						
		 							}
		 							else
		 							{
			 							// The current ParameterCollection does NOT define any ARGH_TYPE_COMMAND parameters
			 							// This Rule can't be used to process this $token
			 							// Continue searching next Rule
			 							break;
			 						}
	 							
	 								break;
	 								
	 							case ARGH_SEMANTICS_VARIABLE:
	 							
	 								echo "!!! FOUND A NAKED VARIABLE !!!\n";
	 							
	 								// Naked Variable
	 								// Adds a Parameter named '_variable'
	 								// More than one item, causes Argument value to be an array
	 								
	 								// Create a '_variable' parameter, if there isn't one yet
	 								if(!$parameters->hasVariable())
	 								{
		 								// Create a new Parameter for variables
	 									$parameters->addParameter(Parameter::createFromArray(
	 											[
		 											'name'			=>	'_variable',
		 											'type'			=>	Parameter::ARGH_TYPE_VARIABLE
		 										]
		 									)
		 								);
	 									
	 									// Create a new Argument to hold values
	 									$argument[0] = new Argument();
	 									
	 									// Argument gets name '__variable'
	 									$argument[0]->key('_variable');
	 									
	 									// Argument gets type ARGH_TYPE_LIST
	 									$argument[0]->type(Parameter::ARGH_TYPE_LIST);
	 									
	 									// Init empty array for Argument value
	 									$argument[0]->value(array($token));
	 								}
	 								else
	 								{
		 								//echo "!!! _variable Parameter already exists !!!\n";
		 								
		 								// Retrieve existing _variable Argument
		 								$a = $arguments->get('_variable');
		 								
		 								// Argument gets $token as a value element (in list)
		 								$list = $a->value(); // Get existing values list
		 								$list[] = $token;	// Add $token to list
		 								$a->value($list); // Update Argument values

		 							}
	 								
	 								break;
	 								
	 							default:
	 							
	 								throw new ArghException(__METHOD__ . ': Token has unknown semantic meaning.');
	 						}
	 						
	 					} // END: for($j=1; $j<count($matches); $j++)
	 					
	 					if(count($argument) > 0)
	 					{
	 						// Add the new Argument(s) (from array) to Arguments
	 						foreach($argument as &$a)
	 						{
			 					try
			 					{
				 					// Validate arguments before adding to the ArgumentCollection
				 					// Invalid arguments will cause an ArghException
			 						ArgumentValidator::validate($a, $parameters);
			 						
			 						$arguments->addArgument($a);
			 					}
			 					catch(ArghException $e)
			 					{
				 					throw $e;
				 				}
		 					} // END: foreach($argument as &$a)
	 						
	 						break; // stop checking rules
	 					} // END: if(count($argument) > 0)
	 					else
	 					{
		 					// This Rule did not create any Arguments, keep checking with next Rule
		 				}
						
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