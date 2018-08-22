<?php
	 
namespace NetFocus\Argh;

class ArghParameterParser
{
	
  public static function parse(array $parameters)
  {
	  
	 	if( count($parameters) == 0 )
		{
			throw new ArghException(__METHOD__ . ': \$parameters array is empty.');
		}
	  
		// Create a new array to save processed parameters
		// We will return this at the end of the method
		$processedParameters = array();
  
		// process an array of parameters to use for parsing arguments
		foreach($parameters as $param)
		{
			/* Example $param
			[
				'name'			=>			'debug',
				'flag'			=>			'd',
				'type'			=>			'boolean',
				'required'	=>			FALSE,
				'default'		=>			FALSE,
				'text'			=>			'Enables debug mode.'
			]
			*/
			
			// Rebuild $param in a $tmp array
			$tmp = array();
			
			// Check for required elements
			if( array_key_exists('name', $param) )
			{
				$tmp['name'] = $param['name'];
			}
			else
			{
				throw new ArghException('Parameter definitions missing required name');
			}
			
			if( array_key_exists('flag', $param) )
			{
				$tmp['flag'] = $param['flag'];
			}
			
			if( array_key_exists('type', $param) )
			{
				if( in_array($param['type'], ['boolean','string']) )
				{
					$tmp['type'] = $param['type'];
				}
				else
				{
					throw new ArghException('Parameter ' . $tmp['name'] . ' has an invalid type');
				}
			}
			else
			{
				// Assign default type
				$tmp['type'] = 'boolean';
			}
			
			if( array_key_exists('required', $param) )
			{
				if($param['required'])
					$tmp['required'] = TRUE;
				else
					$tmp['required'] = FALSE;
			}
			else
			{
				// Assign default required
				$tmp['required'] = FALSE;
			}
			
			if( array_key_exists('default', $param) )
			{
				//! TODO: confirm that default value matches type of this paramter
				$tmp['default'] = $param['default'];
			}
			
			if( array_key_exists('text', $param) )
			{
				$tmp['text'] = $param['text'];
			}
			
			// Add the $tmp param to this objects $parameters array
			array_push($processedParameters, $tmp);
			
		} // END: foreach($parameters as $param)
		
		return $processedParameters;


	} // END: public static function parse()
	
	public static function merge(array &$parameters, array $arguments)
	{

	 	if( count($parameters) == 0 )
		{
			throw new ArghException(__CLASS__ . '::' . __METHOD__ . ' \$parameters array is empty.');
		}
		
		foreach($arguments as $argument)
		{
			echo "DEBUG: Merging argument '" . $argument['key'] . "' into parameters\n";
			
			// Search for a parameter with 'name' or 'flag' $argument->key
			foreach($parameters as &$parameter)
			{
				if( ($parameter['name'] == $argument['key']) || ($parameter['flag'] == $argument['key']) )
				{
					// Add $argument to this $parameter
					$parameter['argument'] = $argument;
					echo "DEBUG: Argument with key '" . $argument['key'] . "' matches parameter named '" . $parameter['name'] . "'\n";
					break; // Stop searching, continue with the next $argument
				}
				
			} // END: foreach($parameters as $parameter)
			
			//throw new ArghException(__METHOD__ . ': Argument with key \'' . $argument['key'] . '\' does not match any defined parameters.');
			
		} // END: foreach($arguments as $argument)
		
	} // END: public static function merge()
	
	public static function map(array $parameters)
	{
		$map = array();
		
		for($i=0; $i<count($parameters); $i++)
		{
			$map[$parameters[$i]['name']] = $i;
			
			if( (array_key_exists('flag', $parameters[$i])) && (!empty($parameters[$i]['flag'])) )
			{
				$map[$parameters[$i]['flag']] = $i;
			}
			
		}
		
		return $map;
	}
	
} 