# KeyNested

- `v::keyNested(string $name)`
- `v::keyNested(string $name, v $validator)`
- `v::keyNested(string $name, v $validator, boolean $mandatory = true)`

Validates an array key or an object property using `.` to represent nested data.

Validating keys from arrays or `ArrayAccess` instances:

```php
$array = [
    'foo' => [
        'bar' => 123,
    ],
];

v::keyNested('foo.bar')->validate($array); // true
```

Validating object properties:

```php
$object = new stdClass();
$object->foo = new stdClass();
$object->foo->bar = 42;

v::keyNested('foo.bar')->validate($object); // true
```

This rule was inspired by [Yii2 ArrayHelper][].

***
See also:

  * [Attribute](Attribute.md)
  * [Key](Key.md)
  * [KeyValue](KeyValue.md)

[Yii2 ArrayHelper]: https://github.com/yiisoft/yii2/blob/68c30c1/framework/helpers/BaseArrayHelper.php "Yii2 ArrayHelper"
