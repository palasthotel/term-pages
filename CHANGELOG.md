# Changelog

## [2.0.0](https://github.com/palasthotel/term-pages/compare/v1.0.3...v2.0.0) (2026-07-31)


### ⚠ BREAKING CHANGES

* the unauthenticated wp_ajax_nopriv_tp_lookup endpoint has been removed, the page search now requires a logged-in user with the capability to edit terms of the taxonomy. The plugin requires WordPress 6.2 and PHP 7.4, and the bundled remove.js was replaced by admin.js.

### Bug Fixes

* prevent unauthenticated SQL injection in the page lookup (reported by Joao Ramos Maciel, WPScan) ([6c28285](https://github.com/palasthotel/term-pages/commit/6c282857df04a67d5ec3e6b4ced054c9d2c5d12e))

## Changelog

All notable changes to this plugin are documented here.
History prior to this file is in the `== Changelog ==` section of [readme.txt](readme.txt).

<!-- next release will be prepended here by release-please -->
