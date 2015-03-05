# resolver
Acts as a central service container for the Komparu packages

## Examples

```php
// Register your object or closures
$resolver->register('my-alias', 'My\Object');
$resolver->register('my-alias', new My\Object);
$resolver->register('my-alias', function() {
    return new My\Object;
});

// Retrieve them
$resolver->resolve('my-alias');
$resolver->resolve('my-object-with-arguments', ['argument-one', 'argument-two']);

// Tagging your objects or closures
$resolver->tag('my-alias', 'my-tag');
$resolver->tag('my-alias', ['tag-one', 'tag-two']);
$resolver->tag('my-alias', ['index' => 'foo', 'type' => 'bar']);
$resolver->register('my-alias', 'My\Object', ['tag-one', 'tag-two']);

// Retrieve them by tags
$resolver->tagged('my-tag');
$resolver->tagged(['tag-one']);
$resolver->tagged(['tag-one', 'tag-two']);
$resolver->tagged(['index' => 'foo']);
$resolver->tagged(['index' => 'foo', 'type' => 'bar']);
```


## Exceptions

### NotRegisteredException
The Resolver could not resolve the class. The alias
is not registered yet in the Resolver.

### NotResolvableException
The Resolver could not resolve the class. The class or
closure could not be created using reflection.