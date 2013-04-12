<?php namespace Way\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class NoValidationRulesException extends \Exception {}

class Model extends Eloquent {
    protected $errors;

    public function __construct()
    {
        parent:::__construct();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }
    
    public function validate()
    {
        if (! isset(static::$rules) or empty(static::$rules))
        {
            throw new NoValidationRulesException;
        }

        $v = \Validator::make($this->attributes, static::$rules);

        if ($v->passes())
        {
            return true;
        }

        $this->errors = $v->messages();

        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
