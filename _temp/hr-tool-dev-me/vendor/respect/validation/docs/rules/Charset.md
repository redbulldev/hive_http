# Charset

- `v::charset(mixed $charset)`

Validates if a string is in a specific charset.

```php
v::charset('ASCII')->validate('açúcar'); // false
v::charset('ASCII')->validate('sugar');  //true
v::charset(['ISO-8859-1', 'EUC-JP'])->validate('日本国'); // true
```

The array format is a logic OR, not AND.

***
See also:

  * [Alnum](Alnum.md)
  * [Alpha](Alpha.md)
  * [PhpLabel](PhpLabel.md)
