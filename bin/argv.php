<?php

echo "\n********************************************\n";

// Show $argv as registered by PHP CLI

echo "Raw \$argv...\n\n";

for($i=0; $i<count($argv); $i++)
{
	echo "\$argv[$i] = " . $argv[$i] . "\n";
}

echo "\n\n";

//
// Show arguments as they will be considered by Argh
//

// Prepare $argv
$argv = array_slice($argv, 1); // remove $argv[0] ... it contains the cli script name

// Check for arguments with spaces, these were originally quoted on the command line
for($i=0; $i<count($argv); $i++)
{
	$arg = $argv[$i];
	
	if( strpos($arg, ' ') !== FALSE )
	{
		// if argument is part of a list, do NOT include the delimiting comma inside quotes
		if( substr($arg, -1) == ',' )
		{
			$argv[$i] = "'" . substr($arg, 0, -1) . "',"; // Wrap (space containing) argument in single quotes
		}
		else
		{
			$argv[$i] = "'" . $arg . "'"; // Wrap (space containing) argument in single quotes
		}
	}
}

do
{	
	// Combine remaining $argv elements into a single string
	$args = implode(' ', $argv);
	
	echo $args . "\n";
	
	// Slice the last token from $argv (simulate consumption of arguments by ArghArgumentParser)
	$argv = array_slice($argv, 0, -1);
}
while(count($argv)>0);

?>
