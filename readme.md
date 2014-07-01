# Auto-Validate On Save
This package takes care of the model validation process for you. Rather than manually going through the tedious `Validator::make(...)` process, just save the model, and this package will handle the rest.

## Usage
Install the package through Composer.

```js
{
    "require": {
        "laravel/framework": "4.0.*",
        "way/database": "dev-master"
    }
}
```

Then, rather than extending `Eloquent` from your model, extend `Way\Database\Model`, like so:

```php
<?php

class Dog extends Way\Database\Model {

}
```

Alternatively, edit `app/config/app.php`, and add a new item to the `aliases` array:

```php
'Model' => 'Way\Database\Model'
```
Now, your models can simply extend `Model`.

```php
<?php

class Dog extends Model {

}
```

## Validation

This package hooks into Eloquent's `save` event, and automatically validates the model's current attributes against the rules that you have set for your model.

Here's an example of setting validation rules for the model:

```php
<?php

class Dog extends Model {
    protected static $rules = [
        'name' => 'required'
    ];

    //Use this for custom messages
    protected static $messages = [
        'name.required' => 'My custom message for :attribute required'
    ];
}
```

Now, simply save the model as you normally would, and let the package worry about the validation. If it fails, then the model's `save` method will return false.

Here's an example of storing a new dog.

```php
public function store()
{
    $dog = new Dog(Input::all());

    if ($dog->save())
    {
        return Redirect::route('dogs.index');
    }

    return Redirect::back()->withInput()->withErrors($dog->getErrors());
}
```

If using Eloquent's static `create` method, you can use the `hasErrors()` methods to determine if validation errors exist.

```php
$dog = Dog::create(Input::all());

if ($dog->hasErrors()) ...
```
That's it! Have fun.
