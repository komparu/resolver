<?php

use Komparu\Resolver\Resolver;
use Mockery as m;

class ResolverTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testRegister()
    {
        $resolver = new Resolver();
        $resolver->register('test', 'Some/Class');
        $resolver->register('test', function() { });
    }

    /**
     * @depends testRegister
     */
    public function testResolve()
    {
        $resolver = new Resolver();
        $resolver->register('test', function() {
            return new \stdClass();
        });

        $this->assertInstanceOf('stdClass', $resolver->resolve('test'));
    }

    /**
     * @depends testRegister
     */
    public function testResolveWithArguments()
    {
        $resolver = new Resolver();
        $resolver->register('test', function($foo, $bar) {
            return new ResolvableClassWithArguments($foo, $bar);
        });

        $this->assertInstanceOf(ResolvableClassWithArguments::class, $resolver->resolve('test', ['foo-value', 'bar-value']));
    }

}

class ResolvableClassWithArguments
{
    public function __construct($foo, $bar)
    {

    }
}