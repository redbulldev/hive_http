# Slug

- `v::slug()`

Validates slug-like strings:

```php
v::slug()->validate('my-wordpress-title'); // true
v::slug()->validate('my-wordpress--title'); // false
v::slug()->validate('my-wordpress-title-'); // false
```

***
See also:

  * [PhpLabel](PhpLabel.md)
  * [Url](Url.md)
  * [VideoUrl](VideoUrl.md)
