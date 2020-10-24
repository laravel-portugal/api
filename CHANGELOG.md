# Changelog

All notable changes to `laravel-portugal/api` will be documented in this file

## [Unreleased]

### Added

- First version of the API documentation
- A guest should be able to login and logout (#37)
- An authenticated user can post an answer to a question (#31)
- An authenticated user can update a question (#27)

### Changed

- Updated Accounts' domain endpoints structure
- Changed timestamps columns on links and tags tables (#28, #29)

### Deprecated

- N/A

### Removed

- N/A

### Fixed

- N/A

### Security

- N/A

## 1.1.0 - 2020-10-08

### Added

- Add account creation (#22)

### Fixed

- Using active_url validation rule breaks tests on Links domain (#35)

## 1.0.5 - 2020-10-05

### Added

- Add hard limit of unnapproved submissions per e-mail (#17)

### Fixed

- Verify link is valid URL (#23)

## 1.0.4 - 2020-09-19

### Added

- Title to links

## 1.0.3 - 2020-09-19

### Changed

- Updated Laravel to v8.x. 

## 1.0.2 - 2020-06-16

### Changed

- Updated Laravel to v7.15.0. 

## 1.0.1 - 2020-04-02

### Security

- Bump symfony/http-foundation from 5.0.5 to 5.0.7 due to security fixes

## 1.0.0 - 2020-03-28

### Added

- Support to receive link's submissions with tag's relation for grouping purposes
