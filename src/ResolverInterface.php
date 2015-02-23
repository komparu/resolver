<?php namespace Komparu\Resolver;

interface ResolverInterface
{
    /**
     * Add a closure or class to the resolver and register it
     * under a simple alias.
     *
     * @param string $alias
     * @param $callbackOrClass
     */
    public function register($alias, $callbackOrClass);

    /**
     * Get a concrete implementation registered under an alias.
     *
     * @param string $alias
     * @param array $arguments
     * @return mixed
     */
    public function resolve($alias, Array $arguments = []);

    /**
     * Check if the alias is registered yet.
     *
     * @param string $alias
     * @return bool
     */
    public function has($alias);

}