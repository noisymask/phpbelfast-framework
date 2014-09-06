<?php

namespace PhpBelfast\Menu;

use Slim\Helper\Set;

use Knp\Menu\Renderer\RendererProviderInterface;

class RendererProvider implements RendererProviderInterface {

    /**
     * @var \Slim\Helper\Set
     */
    private $container;

    /**
     * @var string
     */
    private $defaultRenderer;

    /**
     * @var array
     */
    private $rendererIds;

    /**
     * @param \Slim\Helper\Set $container
     * @param $defaultRenderer
     * @param array $rendererIds
     */
    public function __construct(Set $container, $defaultRenderer, array $rendererIds)
    {
        $this->container = $container;
        $this->rendererIds = $rendererIds;
        $this->defaultRenderer = $defaultRenderer;
    }

    /**
     * @param null $name
     * @return \Knp\Menu\Renderer\RendererInterface
     * @throws \InvalidArgumentException
     */
    public function get($name = null)
    {
        if (null === $name) {
            $name = $this->defaultRenderer;
        }

        if (!isset($this->rendererIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The renderer "%s" is not defined.', $name));
        }

        return $this->container[$this->rendererIds[$name]];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->rendererIds[$name]);
    }

} 