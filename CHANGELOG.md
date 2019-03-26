# Changelog
All notable changes to this project will be documented in this file.

- The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
- This project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

## [1.0.3-alpha] - Coming soon

## Planned
- Complete PHPDocumentor comments
- Add more PHPUnit tests

## Changed
- Use [PHPDocumentor](https://phpdoc.org/) style comments throughout source code

## Added
- [PHPDocumetor](https://phpdoc.org/) configuration file added

## [1.0.2-alhpa] - 2019-03-22

### Changed
- Updated bin/argh.php test program, to be useable insider PHAR archive
- VariableParameter overrides Parameter::createWithAttributes() to force constant name ARHG_NAME_VARIABLE
- bin/argh.php 'about' command has distinct output from 'version' command

### Added
- This and all future releases will include a phar archive as a github release asset

## [1.0.1-alpha] - 2019-03-20

### Fixed
- Fixed bug that causes extra newlines when autoloading Parameter subclasses
- Fixed bug that allows erroneous matches with 'naked multiflag' rule

### Added
- New About.php class defines variables used by bin/argh.php to display versioning info

### Changed
- VariableParameters are no longer automatically added to Argh

## [1.0.0-alpha] - 2019-01-25

### Added
- Parameter subclasses: ParameterBoolean, ParameterCommand, ParameterInteger, ParameterList, ParameterString, ParameterVariable

### Changed
- Parameters re-factored with subclasses for each Parameter type
- Language is no longer Singleton, constructor is now public
- Argument drops hybrid getter/setter for distinct getters and setters
- Argument drops $type property
- Updated and expanded PHPUnit tests
- bin/argh.php updated to match new Argh interface
- Rename Argh::parseWithParameters() as Argh::parse()
- Rename Argh::parseStringWithParameters() as Argh::parseString()
- Rename Argh->parse() as Argh->parseArguments()

### Removed
- ArgumentValidator
- tests/ArgumentCollectionTest (ArgumentCollection was previously removed)
- Argh::command()
- Argh::parameterString()

## [0.1.0] - 2018-09-25

### Added
- Initial development release
- Adopt [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standards
- Adopt [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

# End

<!---
# Section Template
## [x.y.x] - yyyy-mm-dd
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
-->
