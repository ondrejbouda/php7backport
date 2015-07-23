# php7backport
tool for backporting PHP7 scripts to PHP5

[![Build Status](https://travis-ci.org/ondrejbouda/php7backport.svg?branch=master)](https://travis-ci.org/ondrejbouda/php7backport)

* [What is it for?](#what-is-it-for)
* [Usage](#usage)
* [Transformations](#transformations)
* [What is missing?](#what-is-missing)

## What is it for?

It is a tool for backporting PHP 7 code into PHP 5. It works by parsing the source script (with nikic/php-parser) and transforming the new PHP 7 features into equivalent expressions using PHP 5 syntax. The transformed parts are patched into the original code, so as to **retain as much of the original formatting as possible**. For example when changing the header of function (type hints, return type), only the modified header is patched back to the original code.

Included is a script for recursive conversion of whole directory.

## Usage

Clone repo, run ```php convert.php <source dir> <destination dir>```. All *.php files from source dir will be copied to destination dir and backported, retaining the folder structure. No other files will be converted.

## Transformations

Currently, these tranformations of PHP 7 structures are supported:

### Null Coalesce Operator

#### Example 1
```php
$foo ?? $bar;
```
becomes
```php
isset($foo) ? $foo : $bar;
```

#### Example 2
```php
42 ?? $bar;
```
becomes
```php
!is_null(42) ? 42 : $bar;
```

### Return Type Declarations

#### Example
```php
function foo() : SomeClass {}
```
becomes
```php
function foo() {}
```

### Scalar Type Declarations

#### Example
```php
function foo(string $x, SomeClass $y) {}
```
becomes
```php
function foo($x, SomeClass $y) {}
```

### Spaceship operator (Combined Comparison Operator)

#### Example
```php
$foo <=> $bar;
```
becomes
```php
$foo > $bar ? 1 : ($foo < $bar ? -1 : 0);
```

### Deprecation of PHP 4-Style Constructors

#### Example
```php
class Foo
{
    function Foo($bar) {}
}
```
becomes
```php
class Foo
{
    function __construct($bar) {}
}
```

### Anonymous Classes

#### Example
```php
$util->setLogger(new class("test.log") {
    function __construct($file) {}
    public function log($msg)
    {
        echo $msg;
    }
});
```
becomes
```php
$util->setLogger(new AnonymousClass_1(\'test.log\'));

class AnonymousClass_1
{
    function __construct($file)
    {
    }
    public function log($msg)
    {
        echo $msg;
    }
}
```

Class declaration is appended to the end of source file. Multiple anonymous classes are numbered accordingly.

### intdiv() function

#### Example
```php
intdiv(10, 3);
```
becomes
```php
(int) floor(10 / 3);
```

### Complex example
Transformations are applied using operator precedence etc. and even more complex code is transformed correctly.

```php
function foo(string $x, SomeClass $y) : int
{
    return $foo ?? $one <=> $two;
}
```
becomes
```php
function foo($x, SomeClass $y)
{
    return isset($foo) ? $foo : ($one > $two ? 1 : ($one < $two ? -1 : 0));
}
```

## What is missing?

* Unicode Codepoint Escape Syntax
* Closure call() Method
* Filtered unserialize()
* IntlChar Class
* Expectations
* Group use Declarations
* Generator Return Expressions
* Generator Delegation

and other features... Some of them are not trivial to implement.
