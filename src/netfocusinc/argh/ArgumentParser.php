<?php
	
namespace netfocusinc\argh;

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
	
	//
	// PRIVATE PROPERTIES
	//
	
	private $language;
	private $parameterCollection;
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(Language $language, ParameterCollection $parameterCollection)
	{
		// Init properties on this instance
		$this->language = $language;
		$this->parameterCollection = $parameterCollection;
	}
	
		
	/**
		* Summary.
		*
		* Parse $args to create an array of Arguments
		*
		* @param array $args A pre-processed $argv array
		*
		* @return Argument[]
		* @throws
		*
		*/
	public function parse(array $args): array
	{
		// Init an array of Arguments
		$arguments = array();
			
		if(count($args) == 0)
		{
			// Nothing to parse
			return $arguments;
		}
		
		// Get all Rules from Langugage
		$rules = $this->language->rules();
		
		// Get all Parameters from ParameterCollection
		$params = $this->parameterCollection->all();
		
		if( count($rules) == 0 )
		{
			throw new ArghException(__CLASS__ . ': Language needs at least one rule to parse arguments.');
		}
		
		if( count($params) == 0 )
		{
			throw new ArghException(__CLASS__ . ': ParameterCollection needs at least one parameter to parse arguments.');
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
				// DEBUG: Show detailed contents of each variable
				//
				
						echo "\n\n";
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
						echo "\n\n";
						
						//echo "\nDEBUG: Considering: " . $argsS . " ... \n\n";
				
				//
				// END DEBUG
				//
				
				foreach($rules as $rule)
				{
					echo "DEBUG: Checking for match with rule: " . $rule->name() . " (" . $rule->syntax() . ")" . "\n";
					
					$tokens = array(); // init array to capture matching tokens from Rule->match()
					
					if( $rule->match($argsS, $tokens) )
					{
						// Count the number of arguments that were matched
						$count = count($argsL);
						
						//echo "* MATCHED $count \$argv elements *\n";
						
						// Empty $argsL; prevent this inner foreach loop from continuing
						for($i=0; $i<$count; $i++) array_shift($argsL);
						
						// Remove (shift) matching elements from $args
						// These arguments have been consumed by the parser and are no longer needed
						for($i=0; $i<$count; $i++) array_shift($args);
 						
 						//
 						// Try yielding Arguments from this Rule
 						// If this Rule does not yield any Arguments, continue checking the next Rule
 						//
 						
 						$yield = $this->yieldArgumentsFromRule($rule, $tokens);
						
	 					if( count($yield) > 0 )
	 					{
		 					//? TODO: Validate Arguments before adding them to the Arguments array?
		 					
		 					// Add the new Arguments yielded from this Rule
		 					foreach($yield as $y) $arguments[] = $y;
	 						
	 						// !IMPORTANT! Stop checking Rules
	 						break; 
	 						
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
						throw new ArghException(__METHOD__ . ': Syntax Error: ' . $argsS);
					}
					
				} // END: if( count($tokens) == 0 )
				
			} // END do
			while( count($argsL) > 0 );
			
		} // END: do
		while( count($args) > 0 );
		
		// Return Arguments array
		return $arguments;
		
	} // END: public static function parse()
	
	/**
		* Attempts to create Arguments give a Rule and matching tokens
		*
		* Long Descr
		*
		* @internal
		*
		* @param $rule Rule
		* @param $tokens string[]
		*
		* @return Argument[] An array of Arguments
		*/
	public function yieldArgumentsFromRule(Rule $rule, array $tokens): array
	{
		
		// Create an array of new Argument
		// In most cases, a single Rule will create a single Arugment
		// Unless the Rule contains an ARGH_SEMANTICS_FLAGS, which creates an Argument for each flag
		
		$argument = array();

		// Loop through $tokens and define Argument(s) based on the current rules semantics
		for($i=1; $i<count($tokens); $i++)
		{
			$token = $tokens[$i];
			$semantics = $rule->semantics()[$i-1];

			//echo __METHOD__ . ": token: $token (" . Rule::semanticsToString($semantics) . ")\n";

			switch( $semantics )
			{ 
				case ARGH_SEMANTICS_FLAG:
				
					if( $this->parameterCollection->exists($token) )
					{	
						// This Rule will create a single Argument
						if(count($argument)==0) $argument[0] = new Argument($token);								
					}
					else
					{
						// This token does NOT match the flag of any defined parameter
						// This Rule will NOT yield any arguments
						break 2; // Break from this switch and for loop
					}
					
					break;
					
				case ARGH_SEMANTICS_FLAGS:
					
					// Check every character of this $token for a matching parameter 'flag'
					for($j=0; $j<strlen($token); $j++)
					{
					
						if( $this->parameterCollection->exists( $token{$j} ) )
						{
							// This Rule can only apply to ARGH_TYPE_BOOLEAN Parameters
							if( ARGH_TYPE_BOOLEAN == $this->parameterCollection->get($token{$j})->getParameterType() )
							{
								// Create new Argument for each flag
								if( !array_key_exists($j, $argument) ) $argument[$j] = new Argument($token{$j});
							}
						}
						else
						{
							// A character in $token, does not match a defined Parameter flag
							// Undo the creation of new Arguments under this Rule
							$argument = array();
						}		
						
					} // END: for($j=0; $j<strlen($token); $j++)
					
					break;
					
				case ARGH_SEMANTICS_NAME:
			
					if( $this->parameterCollection->exists($token) )
					{
						// This Rule will create a single Argument
						if(count($argument)==0) $argument[0] = new Argument($token); 								
					}
					else
					{
						// This token does NOT match the flag of any defined parameter
						// This Rule will NOT yield any arguments
						break 2; // Break from this switch and for loop
					}
			
					break;			
					
				case ARGH_SEMANTICS_VALUE:
				
					// Usually, the Argument will have already been created by another token in this Rule
				
					// If no new Argument created by this Rule yet, create one now
					if(count($argument)==0) $argument[0] = new Argument();
					
					// The new Argument's 'key' should be set by another token in this Rule
					$argument[0]->setValue($token);
					
					break;
					
				case ARGH_SEMANTICS_LIST:
				
					// Usually, the Argument will have already been created by another token in this Rule
				
					// If no new Argument created by this Rule yet, create one now
					if(count($argument)==0) $argument[0] = new Argument();
				
					// Trim brackets from the $token (list)
					$token = trim($token, "[]");
					
					// Explode comma seperated list into elements
					$elements = explode(',', $token);
					
					// Use the $elements array as the 'value' for all new Argument created by this Rule
					// Usually, this will only apply to a single Argument, unless this Rule contains ARGH_SEMANTICS_FLAGS
					foreach($argument as &$a) $a->setValue($elements);
				
					break;
					
				case ARGH_SEMANTICS_COMMAND:
				
					// Check if ParameterCollection contains any commands
					if($this->parameterCollection->hasCommand())
					{
						// Retrieve all ARGH_TYPE_COMMAND Parameters
						$commands = $this->parameterCollection->getCommands();
							
						foreach($commands as $p)
						{
							if($p->hasOptions())
							{
								if( in_array($token, $p->getOptions()) )
								{
									// $token matches an option of this ARGH_TYPE_COMMAND Parameter	
									
				 					// If no new Argument created by this Rule yet, create one now
				 					if(count($argument)==0) $argument[0] = new Argument($p->getName(), $token);
									
									// Stop searching this Parameters options
									break;
									
								} // END: if( in_array($token, $p->options()) )
							} // END: if($p->hasOptions())
						} // END: foreach($commands as $p)
							

					} // END: if($this->parameterCollection->hasCommand())
				
					break;
					
				case ARGH_SEMANTICS_VARIABLE:
					
					// Create a new Argument to hold values
					$argument[0] = new Argument(Parameter::ARGH_NAME_VARIABLE, $token);
					
					break;
					
				default:
				
					throw new ArghException(__CLASS__ . ': Token has unknown semantic meaning.');
			}
			
		} // END: for($j=1; $j<count($matches); $j++)
		
		
		//echo 'Yielded Arguments:' . "\n";
		//print_r($argument);
		
		// Return an array of Arguments yielded by this Rule
		return $argument;
		
	} // END: yieldArgumentsFromRule(Rule $rule, array $tokens): array
	
}