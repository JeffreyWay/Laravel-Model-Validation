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
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

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

    /**
     * Does the model have validation errors?
     */
    public function wasSaved()
    {
        return empty($this->errors);
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return ! $this->wasSaved();
    }

}
