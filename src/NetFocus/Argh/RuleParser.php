<?php
	
namespace NetFocus\Argh;

class RuleParser
{
	public static function parse(array $rules)
	{
		if( (isset($rules)) && (count($rules)>0) )
		{
			foreach($rules as $rule)
			{
				// Make sure the rule contains all required elements
				if( !array_key_exists('name', $rule) )
				{
					throw new ArghException('Rule is missing required name');
				}
				
				if( !array_key_exists('example', $rule) )
				{
					throw new ArghException('Rule is missing required example');
				}
				
				if( !array_key_exists('syntax', $rule) )
				{
					throw new ArghException('Rule \'' . $rule['name'] . '\' is missing required syntax');
				}
				else
				{
					// Validate the syntax regular expression
					// Suppress error messages
					if( @preg_match($rule['syntax'], '') === FALSE )
					{
						throw new ArghException('Rule \'' . $rule['name'] . '\' syntax \'' . $rule['syntax'] .  '\' is not a valid regular expression');
					}
				}
				
				if( !array_key_exists('semantics', $rule) )
				{
					throw new ArghException('Rule \'' . $rule['name'] . '\' is missing required semantics');
				}
				else
				{
					if( !is_array($rule['semantics']) )
					{
						throw new ArghException('Expecting array for rule \'' . $rule['name'] . '\' semantics, ' . gettype($rule['semantics']) . ' given');
					}
					else
					{
						// Confirm count(semantics) matches number of parenthesized subpatterns defined by the syntax regular expression
						$syntax = str_replace(array('\(','\)'), '', $rule['syntax']); // remove escaped parenthesis; used for matching ARGH_SYN_LIST
						if( substr_count($syntax, '(') != count($rule['semantics']) )
						{
							throw new ArghException('Rule \'' . $rule['name'] . '\' syntax defines ' . substr_count($rule['syntax'], '(') . ' sub-patterns, but semantics defines ' . count($rule['semantics']));
						}
						
					}
				}
				
			} // END: foreach($rules as $rule)
			
			return true;
			
		} // END: if( (isset($rules) && (count($rules)>0) )
		else
		{
			throw new ArghException('Empty rule set given');
		}
	}
	
}

?>