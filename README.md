# Dumper
Dumps information about a variable. Replacement of standard var_dump function

## Installation
Install the package through [Composer](https://getcomposer.org/).

Run the Composer require command from the Terminal:  
```
composer require faydaen/dumper --dev
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
    class User {
        public $name;
        public $age;
        protected $someProtectedField;

        public function __construct (){
            $this->name = 'John Doe';
            $this->age = 32;
        }
    }
    // will be shown only public fields
    dd(new User());
```
