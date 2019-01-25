# Argh

Parse PHP command line arguments with ease so you can focus on your CLI application.

Detailed information and usage examples are available at [https://www.netfocusinc.com/argh](Argh PHP Command Line Parser)

# ALPHA RELEASE

This project is currently under **alpha** release. Beta release will follow after the addition of more rigorous PHPUnit tests.

### Prerequisites

This project has been developed and tested with the following (see list below), and has yet to be tested using other operating systems or versions of PHP.

- PHP 7.2.14
- CentOS 7.6

### Installing

There are currently two methods of installing Argh. We plan on adding a Phar (PHP Archive) distribution soon.

1. Composer
2. Github

More detailed instructions can be found at [https://www.netfocusinc.com/argh#install](Argh Installation Instructions)

#### Composer Installation

[https://getcomposer.org/](Composer) is the preferred method for installation.

```
$ composer require netfocusinc/argh
$ php vendor/netfocusinc/argh/bin/argh.php --about
Argh! by Benjamin Hough, Net Focus Inc.
```

#### Github Installation

Releases can be downloaded from [https://github.com/netfocusinc/argh/releases](Argh's Github Releases).

Alternatively, the Argh [https://github.com/netfocusinc/argh](Github repository) can be cloned.

```
$ git clone https://github.com/netfocusinc/argh.git
```

### Basic Usage

Using Argh is an easy way to parse arguments to your PHP command line scripts.
See more examples of [https://www.netfocusinc.com/argh#usage](how to use Argh) are available.

```
<?php

	// Create a new Argh instance
	$argh = new Argh(
  	[
    	StringParameter::createWithAttributes( [ 'name' => 'message' ] )		
		]
	);
 
	// Let Argh parse PHP's $argv array
	$argh->parse($argv);
	
	// Access run-time values of the arguments that were supplied to your CLI script
	echo $argh->message;

?>
```

## Running Unit Tests

The [https://phpunit.de/index.html](PHPUnit) tests for Argh can be run like this.

```
$ vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests/
```

## Built With

* [PHP](http://php.net/) - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Composer](https://getcomposer.org/) - Dependency Manager for PHP
* [PHPUnit](https://phpunit.de/) - Testing Framework

## Contributing

Coming soon...

## Versioning

This project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

For the versions available, see the [tags on this repository](https://github.com/benjaminhough/Argh/tags).

A [Changelog](https://github.com/netfocusinc/argh/blob/master/CHANGELOG.md) is also available on Github.

## Authors

See the list of [contributors](https://github.com/benjaminhough/Argh/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
