<?php
namespace PhpBelfast\Menu;

use Slim\Helper\Set;
use Slim\Http\Request;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Voter\UriVoter;

use Knp\Menu\Twig\Helper as TwigHelper;
use Knp\Menu\Twig\MenuExtension;

class Provider
{

    /**
     * @var RendererProvider
     */
    private $rendererProvider;


    /**
     * @param Set $container
     * @param Request $request
     * @param array $defaultOptions
     */
    public function __construct(Set $container, Request $request, array $defaultOptions = array())
    {
        $this->rendererProvider = new RendererProvider(
            $container,
            'main',
            array('main' => 'listRenderer')
        );

        $container->set('listRenderer', $this->listRenderer($request, $defaultOptions));
    }


    /**
     * @return MenuExtension
     */
    public function getTwigExtension()
    {
        $menuHelper = new TwigHelper($this->rendererProvider);
        return new MenuExtension($menuHelper);
    }


    /**
     * @param Request $request
     * @param array $defaultOptions
     * @return ListRenderer
     */
    protected function listRenderer(Request $request, array $defaultOptions = array())
    {
        // works out the current menu item
        $matcher = new Matcher();
        $matcher->addVoter(new UriVoter($request->getResourceUri()));
        return new ListRenderer($matcher, $defaultOptions);
    }

} 