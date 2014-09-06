<?php
namespace PhpBelfast\Middleware;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\Twig\Helper as TwigHelper;
use Knp\Menu\Twig\MenuExtension;

use PhpBelfast\Menu\RendererProvider;

class MenuProvider extends \Slim\Middleware
{

    /**
     * @var RendererProvider
     */
    private $rendererProvider;

    /**
     * @var array
     */
    private $defaultOptions;

    /**
     * @param array $defaultOptions
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     *
     */
    public function setup()
    {
        $this->rendererProvider = new RendererProvider(
            $this->app->container,
            'main',
            array('main' => 'listRenderer')
        );

        $this->app->container->set('listRenderer', $this->listRenderer());

        $menuHelper = new TwigHelper($this->rendererProvider);
        $this->app->view->parserExtensions[] = new MenuExtension($menuHelper);

    }

    /**
     * @return ListRenderer
     */
    protected function listRenderer()
    {
        // works out the current menu item
        $matcher = new Matcher();
        $matcher->addVoter(new UriVoter($this->app->request->getResourceUri()));
        return new ListRenderer($matcher, $this->defaultOptions);
    }

    /**
     *
     */
    public function call()
    {
        // Attach as hook.
        $this->app->hook('slim.before', array($this, 'setup'));

        // Call next middleware.
        $this->next->call();
    }
}