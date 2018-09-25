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
class ArgumentValidator
{
	
	/**
		* Summary.
		*
		* Parse $args using $rules and $params to create Arguments
		*
		* @param mixed An Argument, or an array of Arguments to validate
		*
		* @throws ArghException
		*
		*/
	public static function validate(Argument &$argument, ParameterCollection $parameters): void
	{

		switch($argument->type())
		{
			case ARGH_TYPE_BOOLEAN:
			
				// null (no value) considered TRUE (this is how flags work)
				// PHP 'Falsey' values should be considered to mean FALSE
				// Certain character values should be considered to mean FALSE
				// Everything else considered TRUE
					
				if( null === $argument->value() )
				{
 					$argument->value(TRUE);
 				}
 				else if( FALSE == $argument->value() )
 				{
	 				// 'Falsey' (boolean) FALSE, (int) 0, (float 0.0), (string) '0', (string) '', NULL
	 				$argument->value(FALSE);
	 			}
				else if( in_array($argument->value(), array('0', 'false', 'False', 'FALSE', 'off', 'Off', 'OFF')) )
				{
 					$argument->value(FALSE);
 				}
 				else
 				{
	 				$argument->value(TRUE);
	 			}

				//if( !is_bool($a->value()) )
					//throw new ArghException('Argument \'' . $a->key() . '\' should be type \'boolean\', type \'' . gettype($a->value()) . '\' found');
					
				break;
				
			case ARGH_TYPE_INT:
			
				if( !is_int($argument->value()) )
				{
					if( is_numeric($argument->value()) )
					{
						// Convert numeric string to int
						$argument->value(intval($argument->value()));
					}
					else
					{
 						throw new ArghException('Argument \'' . $argument->key() . '\' should be type \'int\', type \'' . gettype($argument->value()) . '\' found');
 					}
				}
				
				break;
				
			case ARGH_TYPE_STRING:
			
				if( !is_string($argument->value()) )
				{
					// Convert to string
					$argument->value(strval($argument->value()));
				}
				
				break;
				
			case ARGH_TYPE_LIST:
			
				if( !is_array($argument->value()) )
				{
					// Force single value into array
					$argument->value(array($argument->value()));
				}
				
				break;
				
			case ARGH_TYPE_COMMAND:
			
				break;
				
			default:
			
				throw new ArghException('Argument \'' . $argument->key() . '\' is missing a \'type\' definition');
		}	// END: switch($argument->type())
		
		//
		// Validate Argument (for options) before adding
		//
		
		// Lookup the Arguments corresponding Parameter
		$p = $parameters->get($argument->key());
		
		// Check for Parameter options
		if($p->hasOptions())
		{
			// Check that the Argument value is a valid option
			if( !$p->isOption($argument->value()) )
			{
				throw new ArghException('Argument \'' . $argument->key() . '\' value \'' . $argument->value() . '\' is not a valid option');
			}
		}
	
	} // END: function validate(Argument &$argument)
		
}