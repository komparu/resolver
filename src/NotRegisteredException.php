<?php namespace Komparu\Resolver;
/**
 * Class NotRegisteredException
 *
 * The Resolver could not resolve the class. The alias
 * is not registered yet in the Resolver.
 *
 * @package Komparu\Resolver
 */
class NotRegisteredException extends \Exception
{
    protected $alias;

    public function __construct($alias = null)
    {
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }
}