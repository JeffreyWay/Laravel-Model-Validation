<?php namespace Way\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent {
    /**
     * Error message bag
     * 
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     * 
     * @var Array
     */
    protected static $rules = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate()
    {
        $v = \Validator::make($this->attributes, static::$rules);

        if ($v->passes())
        {
            return true;
        }

        $this->errors = $v->messages();

        return false;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
