# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased][unreleased]

## [2.4.0] - 2026-05-26

### Added

- Added a direct plugin bootstrap in the main plugin file with an `ABSPATH` guard and gateway registrations.
- Added `.distignore` and Composer build scripts for staged distributions and plugin archives.

### Changed

- Replaced the legacy Grunt-based distribution flow with Composer + WP-CLI build tooling.
- Updated JavaScript development tooling versions in `package.json`.
- Updated plugin source and test files for compatibility with current dependencies.

### Composer

- Added `automattic/jetpack-autoloader` `v5.0.18` (exact from `composer.lock`): upstream tag notes a 15.9-a.3 changelog wording polish in the Jetpack sync. [Release notes](https://github.com/Automattic/jetpack-autoloader/releases/tag/v5.0.18)
- Updated `justinrainbow/json-schema` to `6.8.2` (exact from `composer.lock`): includes a fix to align with the latest JSON Schema test suite. [Release notes](https://github.com/jsonrainbow/json-schema/releases/tag/6.8.2)
- Updated `pronamic/wp-money` to `v2.4.4` (exact from `composer.lock`): includes exception message escaping and static analysis/code quality fixes. [Release notes](https://github.com/pronamic/wp-money/releases/tag/v2.4.4)
- Updated `wp-pay/core` to `v4.33.0` (exact from `composer.lock`): adds `pronamic_pay_register_payment_methods` and moves default payment method registration out of core. [Release notes](https://github.com/pronamic/wp-pay-core/releases/tag/v4.33.0)
- Added `wp-cli/dist-archive-command` `v3.1.0` (exact from `composer.lock`, dev): improves archive behavior (including cleanup of existing zip entries) and command output details. [Release notes](https://github.com/wp-cli/dist-archive-command/releases/tag/v3.1.0)
- Updated `wp-cli/wp-cli-bundle` to `v2.12.0` (exact from `composer.lock`, dev): updates the WP-CLI bundle to the 2.12 release line. [Release notes](https://github.com/wp-cli/wp-cli-bundle/releases/tag/v2.12.0)

Full set of changes: [`2.3.7...2.4.0`][2.4.0]

## [2.3.7] - 2024-12-17

### Commits

- Allow automattic/jetpack-autoloader Composer plugin. ([a295e97](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/a295e9796063fbbc4e76a5e9ed1161389a036808))
- Happy 2024. ([0b0980b](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/0b0980b2a16a4dc04d8c7485973d2b447bcc29b6))

Full set of changes: [`2.3.6...2.3.7`][2.3.7]

[2.3.7]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.6...v2.3.7

## [2.3.6] - 2023-10-13

### Commits

- composer require pronamic/pronamic-cli --dev ([e2623da](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/e2623da6eec13074573cafc6660bad59137fbf41))

Full set of changes: [`2.3.5...2.3.6`][2.3.6]

[2.3.6]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.5...v2.3.6

## [2.3.5] - 2023-09-11

### Commits

- composer require wp-cli/wp-cli-bundle --dev ([0408bb3](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/0408bb3043abd9d03d7df3442b030fecfba26a6a))
- Created .pronamic-build-ignore ([e14aab8](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/e14aab8664968c50b07bb5e114aec12bea902da9))
- Configure WordPress slug. ([5c2ab43](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/5c2ab43812b17d51c884459b89ed651e15ea863d))

Full set of changes: [`2.3.4...2.3.5`][2.3.5]

[2.3.5]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.4...v2.3.5

## [2.3.4] - 2023-06-01

### Commits

- Added `WordPress.WP.I18n` config. ([ab9eefc](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/ab9eefca01785b30dd4ba3d77a0a760168a49e03))
- Fixed text domain. ([30ecf38](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/30ecf38171594e67d5bb924b58b2a993674adcdf))

Full set of changes: [`2.3.3...2.3.4`][2.3.4]

[2.3.4]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.3...v2.3.4

## [2.3.3] - 2023-06-01

### Commits

- Switch from `pronamic/wp-deployer` to `pronamic/pronamic-cli`. ([470a07a](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/470a07ad5c2260be39ffb092a6a5a4e58c82c689))
- Updated .gitattributes ([12ea72c](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/12ea72c5a64f560afa2b8c9a6353d524a41e91c5))

Full set of changes: [`2.3.2...2.3.3`][2.3.3]

[2.3.3]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.2...v2.3.3

## [2.3.2] - 2023-03-27

### Commits

- Tested up to 6.2. ([2493ebd](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/2493ebd59d9b467782207471225b518b7f445934))
- Set Composer type to WordPress plugin. ([2e6c6e4](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/2e6c6e4335e3982e0498fa49caec550e8e54c152))
- Updated .gitattributes ([7c5882f](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/7c5882fcca4ca3e0299984eedb4848fff1afebaf))
- Requires PHP: 7.4. ([2515c7f](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/2515c7f26cd183cb6089ca2927311d89035c2322))

Full set of changes: [`2.3.1...2.3.2`][2.3.2]

[2.3.2]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.1...v2.3.2

## [2.3.1] - 2023-01-31
### Composer

- Changed `php` from `>=8.0` to `>=7.4`.
Full set of changes: [`2.3.0...2.3.1`][2.3.1]

[2.3.1]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.0...v2.3.1

## [2.3.0] - 2022-12-23

### Commits

- Added "Requires Plugins" header. ([2b2f7cf](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/2b2f7cfacf6bf45e3e5f9b519082b3f599f057e1))
- No longer use deprecated `FILTER_SANITIZE_STRING`. ([2b6618a](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/2b6618a8b615f8dcff0387ede8e145e98dcf7910))
- Updated manual URL to pronamicpay.com (pronamic/pronamic-pay#15). ([10e6ec5](https://github.com/pronamic/wp-pronamic-pay-paypal/commit/10e6ec54b027295b3369ed677f7b597140bed52c))

### Composer

- Changed `php` from `>=5.6.20` to `>=8.0`.
- Changed `pronamic/wp-http` from `^1.1` to `v1.2.0`.
	Release notes: https://github.com/pronamic/wp-http/releases/tag/v2.2.2
- Changed `pronamic/wp-money` from `^2.0` to `v2.2.0`.
	Release notes: https://github.com/pronamic/wp-money/releases/tag/v2.2.2
- Changed `wp-pay/core` from `^4.4` to `v4.6.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v2.2.2

Full set of changes: [`2.2.2...2.3.0`][2.3.0]

[2.3.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.2.2...v2.3.0

## [2.2.2] - 2022-09-27
- Updated version number in `readme.txt`.

## [2.2.1] - 2022-09-27
- Update to `wp-pay/core` version `^4.4`.

## [2.2.0] - 2022-09-26
- Updated payment methods registration.

## [2.1.0] - 2022-04-11
- No longer use global core mode.

## [2.0.0] - 2022-01-11
### Changed
- Updated to https://github.com/pronamic/wp-pay-core/releases/tag/4.0.0.

## [1.0.2] - 2021-09-03
- Added payment provider URL filter.

## [1.0.1] - 2021-08-13
- Improved support for tax.

## [1.0.0] - 2021-08-05
- First release.

[unreleased]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.4.0...HEAD
[2.4.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/v2.3.7...v2.4.0
[2.2.2]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/1.0.2...2.0.0
[1.0.2]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/pronamic/wp-pronamic-pay-paypal/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/pronamic/wp-pronamic-pay-paypal/releases/tag/1.0.0
