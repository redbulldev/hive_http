# Overview

[![Build Status](https://img.shields.io/travis/Respect/Validation/master.svg?style=flat-square)](http://travis-ci.org/Respect/Validation)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Respect/Validation/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Respect/Validation/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Respect/Validation/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Respect/Validation/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/respect/validation.svg?style=flat-square)](https://packagist.org/packages/respect/validation)
[![Total Downloads](https://img.shields.io/packagist/dt/respect/validation.svg?style=flat-square)](https://packagist.org/packages/respect/validation)
[![License](https://img.shields.io/packagist/l/respect/validation.svg?style=flat-square)](https://packagist.org/packages/respect/validation)

[The most awesome validation engine ever created for PHP.](http://bit.ly/1a1oeQv)

- Complex rules made simple: `v::numeric()->positive()->between(1, 255)->validate($input)`.
- [Granularity control](feature-guide.md#validation-methods) for advanced reporting.
- More than 100 (fully tested) validators.
- [A concrete API](concrete-api.md) for non fluent usage.
- Works on PHP 5.4+
