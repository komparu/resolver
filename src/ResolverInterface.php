<?php namespace Komparu\Resolver;

interface ResolverInterface
{
    /**
     * Add a closure or class to the resolver and register it
     * under a simple alias.
     *
     * @interface ResolverInterface
     * @param string $alias
     * @param string|array|null $oneOrMoreTags
     * @param        $callbackOrClass
     */
    public function register($alias, $callbackOrClass, $oneOrMoreTags = null);

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

    /**
     * Tag one alias with one or more tags.
     *
     * @param string $alias
     * @param string|array $oneOrMoreTags
     */
    public function tag($alias, $oneOrMoreTags);

    /**
     * Only resolve items that matches all the provided tags.
     *
     * @param array $tags
     * @return array
     * @throws NotRegisteredException
     * @throws NotResolvableException
     */
    public function tagged($oneOrMoreTags);

}