# Argh

Interpret PHP command line arguments with ease so you can focus on your CLI application.

- More about Argh at [Argh! Argument Helper for PHP CLI](https://www.netfocusinc.com/argh)

- Detailed documentation in the [Argh! Wiki](https://github.com/netfocusinc/argh/wiki)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/netfocusinc/argh/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/netfocusinc/argh/?branch=master)

[![Build Status](https://scrutinizer-ci.com/g/netfocusinc/argh/badges/build.png?b=master)](https://scrutinizer-ci.com/g/netfocusinc/argh/build-status/master)

# ALPHA RELEASE

This project is currently under **alpha** release. Beta release will follow after the addition of more rigorous PHPUnit tests.

### System Requirements

This project has been developed and tested with the following (see list below), and has yet to be tested using other operating systems or versions of PHP.

- PHP 7.2.14
- CentOS 7.6

### Installing

There are two methods of installing Argh. Composer is recommended.

1. Composer
2. Phar (PHP Archive)

More detailed instructions can be found at [Argh Installation Instructions](https://github.com/netfocusinc/argh/wiki/Installation)

#### Composer Installation

[Composer](https://getcomposer.org) is the preferred method for installation.

```
$ composer require netfocusinc/argh

$ php vendor/netfocusinc/argh/bin/argh.php about
Argh! by Benjamin Hough, Net Focus Inc. - https://www.netfocusinc.com/argh
```

#### Phar (PHP Archive)

Releases can be downloaded from [Argh's Github Releases](https://github.com/netfocusinc/argh/releases).

```
$ cd path/to/argh
$ php argh.phar about
Argh! by Benjamin Hough, Net Focus Inc. - https://www.netfocusinc.com/argh
```

### Basic Usage

Using Argh is an easy way to interpret command line arguments in your PHP CLI scripts.

When your PHP CLI script is invoked with command line arguments
`$ php myprogram.php --message='Hello World!'`

```
<?php

include 'vendor/autoload.php';

use netfocusinc\argh\Argh;
use netfocusinc\argh\StringParameter;

// Create a new Argh instance
$argh = new Argh(
  [
    StringParameter::createWithAttributes( [ 'name' => 'message' ] )		
  ]
);

// Let Argh parse PHP's $argv array
$argh->parse($argv);

// Access run-time values of the arguments that were supplied to your CLI script
echo $argh->message; // Hello World!

```

See more examples of [how to use Argh](https://github.com/netfocusinc/argh/wiki/Examples) are available.

## Running Unit Tests

The [PHPUnit](https://phpunit.de/index.html) tests for Argh can be run like this.

- When Argh is installed with [Composer](https://getcomposer.org/)

```
$ vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests/
```

## Built With

* [PHP](http://php.net/) - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Composer](https://getcomposer.org/) - Dependency Manager for PHP
* [PHPUnit](https://phpunit.de/) - Testing Framework
* [phar-composer](https://github.com/clue/phar-composer) - Simple phar creation for any project managed via composer.

## Versioning

This project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

For the versions available, see the [GitHub releases page](https://github.com/netfocusinc/argh/releases).

A [Changelog](https://github.com/netfocusinc/argh/blob/master/CHANGELOG.md) is also available on Github.

## Authors

See the list of [contributors](https://github.com/benjaminhough/Argh/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
