<?php
namespace pike\slim;
use Psr\Http\Message\ResponseInterface;
use Jade\Jade;

/**
 * Slim Jade View
 *
 * This is a helper class for Slim to allow for Jade Templates
 * This is built like the Twig View extension and uses the jade-php plugin by
 * Kyle Katarnls
 * 
 * @link https://github.com/kylekatarnls/jade-php
 * 
 */
class JadeView implements \ArrayAccess, \Pimple\ServiceProviderInterface
{
    /**
     * Jade Class
     *
     * @var \Jade
     */
    protected $jade;

    /**
     * The Path
     * 
     * @var Path
     */
    protected $path;
    
    /**
     * Default view variables
     *
     * @var array
     */
    protected $defaultVariables = [];

    /********************************************************************************
     * Constructors and service provider registration
     *******************************************************************************/

    /**
     * Create new Jade View
     *
     * @param string $path     Path to templates directory
     * @param array  $options Jade options
     */
    public function __construct($path, $options = [])
    {
        $this->jade = new \Jade\Jade($options);
        $this->path = $path;
    }

    /**
     * Register service with container
     *
     * @param Container $container The Pimple container
     */
    public function register(\Pimple\Container $container)
    {
        // Register this view with the Slim container
        $container['view'] = $this;
    }

    /********************************************************************************
     * Methods
     *******************************************************************************/

    /**
     * Proxy method to add or overwrite a filter.
     *
     * @param string $name The name of the filter
     * @param JadeFilter $filter The filter to add to the Jade object
     */
    public function addFilter($name, $filter)
    {
        $this->jade->filter($name, $filter);
    }


    /**
     * Fetch rendered template
     *
     * @param  string $template Template pathname relative to templates directory
     * @param  array  $data     Associative array of template variables
     *
     * @return string
     */
    public function fetch($template, $data = [])
    {
        $data = array_merge($this->defaultVariables, $data);
        return $this->jade->render($this->path.$template.'.jade',$data);
    }

    /**
     * Output rendered template
     *
     * @param ResponseInterface $response
     * @param  string $template Template pathname relative to templates directory
     * @param  array $data Associative array of template variables
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, $data = [])
    {
         $response->getBody()->write($this->fetch($template, $data));

         return $response;
    }

    /********************************************************************************
     * Accessors
     *******************************************************************************/

    /**
     * Return the Jade Object
     *
     * @return \Jade
     */
    public function getLoader()
    {
        return $this->jade;
    }

    /********************************************************************************
     * ArrayAccess interface
     *******************************************************************************/

    /**
     * Does this collection have a given key?
     *
     * @param  string $key The data key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->defaultVariables);
    }

    /**
     * Get collection item for key
     *
     * @param string $key The data key
     *
     * @return mixed The key's value, or the default value
     */
    public function offsetGet($key)
    {
        return $this->defaultVariables[$key];
    }

    /**
     * Set collection item
     *
     * @param string $key   The data key
     * @param mixed  $value The data value
     */
    public function offsetSet($key, $value)
    {
        $this->defaultVariables[$key] = $value;
    }

    /**
     * Remove item from collection
     *
     * @param string $key The data key
     */
    public function offsetUnset($key)
    {
        unset($this->defaultVariables[$key]);
    }

    /********************************************************************************
     * Countable interface
     *******************************************************************************/

    /**
     * Get number of items in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->defaultVariables);
    }

    /********************************************************************************
     * IteratorAggregate interface
     *******************************************************************************/

    /**
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->defaultVariables);
    }
}
