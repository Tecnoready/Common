# Historial de cambios
Todos los cambios notables en este proyecto se documentarán en este archivo.

El formato se basa en [Keep a Changelog] (http://keepachangelog.com/en/1.0.0/)
y este proyecto se adhiere a [Semantic Versioning] (http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.1] - 2017-06-20
### Fixed
- fix(correo): se usaba un metodo sin definir y no tomaba el correo de debug
### Added
- feat(correo): se agrego estrucutra asbtracta de correos.
- feat(correo): se agrego adaptador doctrine ORM para enviar correos
- feat(correo): se agregando servicio de correo
### Changed
- refactor(correos): se agrego modelo base de correo.