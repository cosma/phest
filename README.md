Phest
=====


[![Circle CI](https://circleci.com/gh/cosma/phest.svg?style=svg)](https://circleci.com/gh/cosma/phest)



Phalcon + Test = Phest.
A test library for [Phalcon Framework](http://phalconphp.com) version 2.9.




# Table of Contents

 - [Installation](#installation)
 - [Dependencies](#dependencies)
 - [Test Cases](#test-cases)
 - [Retry Tests](#retry-tests)
 - [Run Tests](#run-tests)
 - [License](#license)


# Installation
    
```bash
    $   php composer.phar require cosma/phest '0.0.*'
```
Follow the 'dev-master' branch for latest dev version. But I recommend to use more stable version tags if available.



# Dependencies

Phalcon PHP extension 2.0.13 must be installed. [Phalcon Extension](https://docs.phalconphp.com/uk/latest/reference/install.html)


# Test Cases

Supports the following Test Cases:

* [Unit](#unit-test-case)
* [Web Test Case](#web-test-case)


## Unit Test Case

This case is used for unit testing is an extension of PHPUnit_Framework_TestCase:


```php
use Cosma\Phest\TestCase\UnitTestCase;
 
class SomeVerySimpleUnitTest extends UnitTestCase
{
    public function testSomething()
    {
        $additionClass = new AdditionCLass();
        
        $this->assertEquals(3, $additionClass->calculate(1, 2)); 
    }
}
```
 
 
## Web Test Case

This case is used for functional and controller tests
It has the following methods:

* **mockService** ($serviceName, $mock)
* **getResponse** ($url = '', $requestMethod = 'GET', $parameters = [], $headers = [])


```php
use Cosma\Phest\TestCase\WebTestCase;

class SomeWebFunctionalTest extends WebTestCase
{
    public function setUp()
    {
        /**
        * Required call
        */
        parent::setUp();
        
        $db = $this->getMockBuilder('Phalcon\Db\Adapter\Pdo\Mysql')
            ->disableOriginalConstructor()
            ->setMethods(['query'])
            ->getMock();
        $this->mockService('db', $db);
        
    }
    
    public function testSomething()
    {
        /** @var \Phalcon\Http\Response $response */
        $response = $this->getResponse(
            '/test_endpoint', 'POST', ['test_var' => 'value'], ['Header1' => 223456789, 'Header2' => 'value2']);

        $this->assertInstanceOf('Phalcon\Http\Response', $response);

        $this->assertEquals('200 OK', $response->getStatusCode());
        $this->assertEquals('value', $response->getContent());
        
    }
    
    public function testGetHealthCheck()
    {
        /** @var \Phalcon\Http\Response $response */
        $response = $this->getResponse(
            '/healthCheck', 'GET', [], []);

        $this->assertInstanceOf('Phalcon\Http\Response', $response);

        $this->assertEquals('200 OK', $response->getStatusCode());
    }
}
```


# Retry Tests

Use the @retry annotation for a Class or Method to retry tests in case of failure.
Method annotations are overwriting Class annotation.

```php
use Cosma\Phest\TestCase\UnitTestCase;

/**
* Will retry 10 times all the Class tests that are failing
*
* @retry 10 
*/ 
class SomeVerySimpleUnitTest extends UnitTestCase
{
    /**
    * Will retry 10 times this test if is failing because of the class annotation from above
    */
    public function testFirst()
    {
        // ...
    }
    
    /**
    * Will retry 4 times this test if is failing because of the method annotation from below
    *
    * @retry 4 
    */
    public function testSecond()
    {
        // ...
    }
}
```


## Mockery

[Mockery](https://github.com/padraic/mockery) is a simple yet flexible PHP mock object framework for use in unit testing

```php
use Cosma\Phest\TestCase\UnitTestCase;
       
class SomeUnitTest extends UnitTestCase
{
    public function testGetsAverageTemperatureFromThreeServiceReadings()
    {
        $service = \Mockery::mock('service');
        $service->shouldReceive('readTemp')->times(3)->andReturn(10, 12, 14);

        $temperature = new Temperature($service);

        $this->assertEquals(12, $temperature->average());
    }
}    
```


# Run Tests

vendor/phpunit/phpunit/phpunit -c phpunit.xml.dist --coverage-text --coverage-html=Tests/coverage Tests

# License

The bundle is licensed under MIT.
