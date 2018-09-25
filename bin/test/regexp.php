<?php

echo "\n**********************\n";
echo "Testing Regular Expressions...\n\n";

$regexps = array(
	'List Items Between Brackets'=>'/\[([^\]]+)\]/i'
);

$strings = array(
	'[one, two, three]',
	'[blue, green, blue green]'
);

foreach($regexps as $desc=>$regexp)
{
	echo "\n$desc\n";
	foreach($strings as $string)
	{
		echo "\nTESTING $regexp AGAINST $string ... \n";
		
		$matches = array(); // For matched elements
		
		$match = preg_match($regexp, $string, $matches);
		
		if(FALSE === $match)
		{
			echo "An error occurred attempting match";
		}
		else if(1 == $match)
		{
			echo "Matches: ";
			
			foreach($matches as $m)
			{
				echo "($m) ";
			}
		}
		else if(0 == $match)
		{
			echo "NO MATCH";
		}
		echo "\n\n";
		
	}
}
?>
