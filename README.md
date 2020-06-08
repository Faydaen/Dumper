# Dumper

Dumps information about a variable. Replacement of standard var_dump function

## Install


```
composer require faydaen/dumper
```

**OR**  

Add this to require-dev section in your composer.json
```

```
## Usage and samples

### Dump scalar variables

Dump string
```php
dd('hello');
```

Dump integer
```php
dd(42);
```

Dump float
```php
dd(3.14);
```

Dump bool
```php
dd(true);
```

Dump null
```php
dd(null);
```

### Dump arrays and objects

Dump empty array
```php
dd([]);
```

Dump indexed arrays (without keys)
```php
dd(['cat', 'dog', 'horse']);
```

Dump associates array
 
```php
dd(['planet'=>'Mars', 'hasAtmosphere'=>true, 'satellites'=>2]);
```

Dump object
```php
dd($user);
```

