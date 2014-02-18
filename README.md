cobiss-parser
=============

COBISS parser written in PHP

## Requirements
* PHP 5.4+ (short array syntax support)
* Httpful [[link]](http://phphttpclient.com/)
* php_curl extension enabled

## Usage
Check index.php and example.php
```php
include 'Cobiss.php';
$c = new Cobiss('Ptuj');
$r = $c->search('86-11-16301-X');
$r = Cobiss::parse($r);
if (count($r) > 0) {
    var_dump($r);
} else {
	print('Not found.');
}
```
