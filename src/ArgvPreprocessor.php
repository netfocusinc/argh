<?php
	
namespace NetFocus\Argh;

class ArgvPreprocessor
{
	
	/**
	 * Prepare an array of arguments for parsing
	 *
	 * 
	 * PHP registers the $argv array with input from the command line.
	 * This process includes some items that make it difficult to parse the way Argh wants to.
	 * This function takes an $argv array and creates a new array where ...
	 * Bracketed lists are put in a single element, regardless of spacing
	 * Strings containing spaces are wrapped in single quotes; unless they are part of a list
	 *
	 * 
	 * @param array $args An '$argv' like array of arguments
	 *
	 * @return array
	 */
	
	public static function process(array $argv)
	{
		if(count($argv) < 1)
		{
			throw(new ArghException(__CLASS__ . ': $argv is empty'));
		}
		
		//
		// Remove first element from $argv, it contains the name of the php script
		//
		
		$args = array_slice($argv, 1);
		
		// DEBUG
		//echo "\n------- AFTER SLICING --------\n";
		//print_r($args);
		//echo "\n-------------------------------------\n\n";
		
		//
		// Lists
		//
		
		for($i=0; $i<count($args); $i++)
		{
			// Lists begin with an opening bracket '['
			if( strpos($args[$i], '[') !== FALSE )
			{
				// Found the beginning of a list at $i
				//echo "DEBUG: Found beginning of a list at $i\n";
				
				// Search for the end of the list; starting at the current element				
				for($j=$i; $j<count($args); $j++)
				{
					if( strpos($args[$j], ']') !== FALSE )
					{
						// Found the end of a list at $j
						//echo "DEBUG: Found end of a list at $j\n";
						
						if($j != $i)
						{
							// List does NOT open and close in the same element; elements must be re-combined
						
							// List closing bracket should always be the last character of the element
							if( strpos($args[$j], ']') == (strlen($args[$j])-1) )
							{
								// Create a new string for $args $i thru $j
								$list = "";
								for($k=$i; $k<=$j; $k++) $list .= $args[$k];
								
								//echo "DEBUG: Replace elements $i thru $j with: $list\n";
								
								// Replace $args from $i thru $j with $list
								array_splice($args, $i, ($j-$i)+1, $list);
								
								// Stop searching for the end of the list
								break;
							}
							else
							{
								throw new ArghException(__METHOD__ . ': Syntax Error: Invalid list.');
							}
							
						} // END: if($j != $i)
						else
						{
							//echo "DEBUG: List is already contained in a single element.\n";
						}
						
					} // END: if( ($closeAt = strpos($args[$j], ']')) !== FALSE )
					
				} // END: for($j=$i; $j<count($args); $j++)
				
			} // END: if( strpos($args[$i], ' ') !== FALSE )
			
		} // END: for($i=0; $i<count($args); $i++)		
		
		//
		// Quotes
		//
		
		// Check for arguments with spaces, these were originally quoted on the command line
		for($i=0; $i<count($args); $i++)
		{
			if( strpos($args[$i], ' ') !== FALSE )
			{

				// Check if argument is a list
				if( strpos($args[$i], '[') !== FALSE )
				{
					//! TODO: Process each item in the list separately
					//echo "DEBUG: Quoting items in a list\n";
					
					// Extract the items from the list; they are contained between brackets [ ]
					$tokens = array(); // init array to capture matching tokens from preg_match()
					
					if( preg_match('/\[([^\]]+)\]/i', $args[$i], $tokens) )
					{
						//echo "DEBUG: List items: " . $tokens[1] . "\n";

						// Explode the items to an array
						$list = explode(',', $tokens[1]);
					
						// Process each item in the list separately
						for($j=0; $j<count($list); $j++)
						{

							if( strpos($list[$j], ' ') !== FALSE )
							{
								
								// Choose single or double quotes
									$quote = self::quoteChar($list[$j]);
					
								// Wrap (space containing) items quotes
								$list[$j] = $quote . $list[$j] . $quote;
							}
							
						} // END: for($j=0; $j<count($list); $j++)
					
						// Implode the list to a comma separated string
						$listS = implode(",", $list);
					
						// Put the (processed) list string back between the brackets [ ]
						// Replace the original argument at $args[$i] with the processed one	
						$args[$i] = preg_replace('/\[([^\]]+)\]/i', '[' . $listS . ']', $args[$i]);
						
						
						//$args[$i] = $token[1] . '[' . $listS . ']';
						
					}
					else
					{
						throw new ArghException(__METHOD__ . ': Syntax Error: Invalid list.');
					}
				
				}
				else
				{
					//!TODO: handle --msg="Hello World"; We don't want "--msg=Hello World"
					
					// Choose single or double quotes
					$quote = self::quoteChar($args[$i]);
					
					// Wrap (space containing) argument in single quotes
					$args[$i] = $quote . $args[$i] . $quote;
				}				
				
			} // END: if( strpos($args[$i], ' ') !== FALSE )
			
		} // END: for($i=0; $i<count($args); $i++)	
		
		// DEBUG
		//echo "\n------- AFTER PRE PROCESSING --------\n";
		//print_r($args);
		//echo "\n-------------------------------------\n\n";
		
		return $args;	
	}
	
	private static function quoteChar($arg)
	{
		$quote = "'"; // default
		
		// If certain characters are contained in the argument, it should be nested in double quotes
		// Example: apostrophe (single quote)
		
		if( strpos($arg, "'") !== FALSE ) $quote = '"';
		if( strpos($arg, "!") !== FALSE ) $quote = '"';
		
		return $quote;
		
	}
	
	
}