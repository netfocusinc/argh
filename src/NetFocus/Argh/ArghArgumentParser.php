<?php
	
namespace NetFocus\Argh;

class ArghArgumentParser
{
	
	public static function parse(array $args, array $rules, array $parameters)
	{
		// parse $argv using $rules and $parameters to create an $arguments array
		
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
		
		for($i=0; $i<count($args); $i++)
		{
			echo "\nDEBUG: Considering \$args[$i] " . $args[$i] . " ... \n";
			
			echo count($rules) . " rules to test\n";
			
			foreach($rules as $rule)
			{
				echo "DEBUG: Checking for match with rule: " . $rule['name'] . " (" . $rule['syntax'] . ")" . "\n";
				
				$tokens = array();
				if( preg_match($rule['syntax'], $args[$i], $tokens) )
				{
					echo "DEBUG: " . $args[$i] . " matches syntax pattern " . $rule['syntax'] . "\n";
					
					// Build an argument in a $tmp array
					$tmp = array();
					
					// Loop through $tokens and assign data to $arguments based on the current rules semantics
					for($j=1; $j<count($tokens); $j++)
					{
						$token = $tokens[$j];
						//echo "DEBUG: token: " . $token . "\n";
						// Semantic meaning of token for this rule
						$meaning = $rule['semantics'][$j-1];
						echo "DEBUG: token: " . $token . " (" . $meaning . ")\n";
						
						switch($meaning)
						{
							case Argh::KEY:
								//! TODO: Check if this 'key' matches a defined parameter 'name' or 'flag'
								// if it matches a 'flag', use the corresponding 'name' for this arguments 'key' instead
								// ? if no match, okay to assign to arguments anyway
								$tmp['key'] = $token;
								break;
							case Argh::VALUE:
								$tmp['value'] = $token;
								break;
							case Argh::COMMAND:
								break;
							case Argh::SUBCOMMAND:
								break;
							default:
								// ? Throw exception
						}
						
					} // END: for($j=1; $j<count($matches); $j++)
					
					// Look for a parameter associated with this argument
					$argumentParameter = null;
					
					foreach($parameters as $parameter)
					{
						if($parameter['name'] == $tmp['key'])
						{
							$argumentParameter = $parameter;
						}
						else
						{
							if( array_key_exists('flag', $parameter) )
							{
								if($parameter['flag'] == $tmp['key'])
								{
									$argumentParameter = $parameter;
								}
							}
						}
					} // END: foreach($parameters as $parameter)
					
					if($argumentParameter === null)
					{
						//! TODO: throw ArghUnknownArgumentException; clients can catch this and display 'usage'
						throw new ArghException(__METHOD__ . ': Argument \'' . $tmp['key'] . '\' does not match a defined parameter.');
					}
					
					// Set boolean values for flags to TRUE
					switch($argumentParameter['type'])
					{
						case 'boolean':
						
							if( array_key_exists('value', $tmp) )
							{
								// When value is present, re-set to its literal boolean equivalent
								
								if($tmp['value'])
								{
									$tmp['value'] = TRUE;
								}
								else
								{
									$tmp['value'] = FALSE;
								}
							}
							else
							{
								// When no value is specified, default to TRUE
								$tmp['value'] = TRUE;
							}
							break;
							
						case 'string':
						
							break;
							
						default:
					}
						 
					//! TODO: validate tmp argument before adding to this objects arguments array
					
					
					// Add $tmp argument to this objects $arguments array
					array_push($arguments, $tmp);
					
					break; // move on to the next $argv element
					
				} // END: if( preg_match($rule->syntax, $args[$i], $matches) )
				
			} // END: foreach($rules as $rule)
			
		} // END: for($i=0; $i<count($args); $i++)
		
		return $arguments;
		
	} // END: public static function parse()
	
}