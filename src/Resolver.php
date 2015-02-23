<?php namespace Komparu\Resolver;

use ReflectionException;

class Resolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $services = [];

    /**
     * Add a closure or class to the resolver and register it
     * under a simple alias.
     *
     * @interface ResolverInterface
     * @param string $alias
     * @param        $callbackOrClass
     */
    public function register($alias, $callbackOrClass)
    {
        $this->services[$alias] = $callbackOrClass;
    }

    /**
     * Get a concrete implementation registered under an alias.
     *
     * @interface ResolverInterface
     * @param string $alias
     * @param array $arguments
     * @return mixed
     * @throws NotRegisteredException
     * @throws NotResolvableException
     */
    public function resolve($alias, Array $arguments = [])
    {
        // We can only resolve registered aliases.
        if(!$this->has($alias)) throw new NotRegisteredException();

        try {

            // Resolve a closure or array with class/method.
            if(is_callable($this->services[$alias])) {
                return call_user_func_array($this->services[$alias], $arguments);
            }

            // Get the implementation using reflection.
            $class = $this->services[$alias];
            $reflection = new \ReflectionClass($class);

            return $reflection->newInstanceArgs($arguments);
        }
        catch(ReflectionException $e) {
            throw new NotResolvableException();
        }
    }

    /**
     * Check if the alias is registered yet.
     *
     * @interface ResolverInterface
     * @param string $alias
     * @return bool
     */
    public function has($alias)
    {
        return isset($this->services[$alias]);
    }
}