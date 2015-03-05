<?php namespace Komparu\Resolver;

use ReflectionException;

class Resolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $tagged = [];

    /**
     * Add a closure or class to the resolver and register it
     * under a simple alias.
     *
     * @interface ResolverInterface
     * @param string $alias
     * @param string|array|null $oneOrMoreTags
     * @param        $callbackOrClass
     */
    public function register($alias, $callbackOrClass, $oneOrMoreTags = null)
    {
        $this->services[$alias] = $callbackOrClass;

        $this->tag($alias, $oneOrMoreTags);
    }

    /**
     * Tag one alias with one or more tags.
     *
     * @param string $alias
     * @param string|array $oneOrMoreTags
     */
    public function tag($alias, $oneOrMoreTags)
    {
        $this->tagged[$alias] = (array) $oneOrMoreTags;
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
     * Only resolve items that matches all the provided tags.
     *
     * @param array $tags
     * @return array
     * @throws NotRegisteredException
     * @throws NotResolvableException
     */
    public function tagged($oneOrMoreTags)
    {
        $tags = (array) $oneOrMoreTags;

        $resolved = [];

        foreach($this->tagged as $alias => $tagged) {

            // Only continue if the alias matches the tags
            if(!$this->matchesTags($tagged, $tags)) continue;

            // Only resolve it once.
            if(isset($resolved[$alias])) continue;

            $resolved[$alias] = $this->resolve($alias);
        }

        return array_values($resolved);
    }

    /**
     * Check if the alias tags match against the provided tags.
     *
     * @param array $tagged
     * @param array $tags
     * @return bool
     */
    protected function matchesTags(Array $tagged, Array $tags)
    {
        if($this->isAssoc($tags)) {

            // Only resolve if all tags are matched.
            foreach($tags as $key => $tag) {

                if(!isset($tagged[$key])) return false;

                if(is_array($tag)) {
                    return $this->matchesTags($tag, (array) $tagged[$key]);
                }

                if($tagged[$key] != $tag) return false;
            }

        }
        else {

            // Only resolve if all tags are matched.
            foreach($tags as $tag) {
                if(!in_array($tag, $tagged)) return false;
            }
        }

        return true;
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

    /**
     * Is the array associative?
     *
     * @param array $arr
     * @return bool
     */
    protected function isAssoc(Array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}