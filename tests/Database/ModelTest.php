<?php

use Way\Database\Model;
use Mockery as m;

class ModelTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testValidateSuccess()
    {
        // Mock Validator response and make it pass
        $response = m::mock('StdClass');
        $response->shouldReceive('passes')->once()->andReturn(true);

        $validation = m::mock('Illuminate\Validation\Validator');
        $validation->shouldReceive('make')
                   ->once()
                   ->andReturn($response);

        $model = new Model([], $validation);
        $result = $model->validate();

        // If validation passes, we should return true
        // and not set any errors.
        $this->assertTrue($result);
        $this->assertNull($model->getErrors());
    }

    public function testValidateFail()
    {
        // Mock Validator response and have it fail
        $response = m::mock('StdClass');
        $response->shouldReceive('passes')->once()->andReturn(false);
        $response->shouldReceive('messages')->once()->andReturn('foo');

        $validation = m::mock('Illuminate\Validation\Validator');
        $validation->shouldReceive('make')
                   ->once()
                   ->andReturn($response);

        $model = new Model([], $validation);
        $result = $model->validate();

        // This time we should return false
        // and store the validation messages
        // on the errors property
        $this->assertFalse($result);
        $this->assertEquals('foo', $model->getErrors());
    }

    public function testGetErrors()
    {
        $model = m::mock('Way\Database\Model')->makePartial();
        $model->setErrors('foo');

        $this->assertEquals('foo', $model->getErrors());
    }

    public function testHasErrors()
    {
        $model = m::mock('Way\Database\Model')->makePartial();

        $this->assertFalse($model->hasErrors());

        $model->setErrors('foo');

        $this->assertTrue($model->hasErrors());
    }

}