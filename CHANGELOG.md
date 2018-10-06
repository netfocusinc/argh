# Changelog
All notable changes to this project will be documented in this file.

- The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
- This project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

## [Unreleased]

### Added
- Parameter subclasses: ParameterBoolean, ParameterCommand, ParameterInteger, ParameterList, ParameterString, ParameterVariable

### Changed
- Parameters re-factored with subclasses for each Parameter type
- Language is no longer Singleton, constructor is now public
- Argument drops hybrid getter/setter for distinct getters and setters
- Argument drops $type property
- Updated and expanded PHPUnit tests
- bin/argh.php updated to match new Argh interface

### Deprecated

### Removed
- ArgumentValidator
- tests/ArgumentCollectionTest (ArgumentCollection was previously removed)
- Argh::command()
- Argh::parameterString()

### Fixed
### Security

## [0.1.0] - 2018-09-25

### Added
- Initial development release
- Adopt [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standards
- Adopt [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

# End

# Section Template
## [x.y.x] - yyyy-mm-dd
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
