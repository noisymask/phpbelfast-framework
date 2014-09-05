<?php

$app->container->set('listRenderer', function() use ($app) {
    $matcher = new \Knp\Menu\Matcher\Matcher();
    $matcher->addVoter(new \Knp\Menu\Matcher\Voter\UriVoter($app->request->getResourceUri()));
    return new \Knp\Menu\Renderer\ListRenderer($matcher);
});


$app->hook('slim.before', function() use ($app){

    $rendererProvider = new \PhpBelfast\Menu\RendererProvider(
        $app->container,
        'main',
        array('main' => 'listRenderer')
    );

    $helper = new \Knp\Menu\Twig\Helper($rendererProvider);
    $menuExtension = new \Knp\Menu\Twig\MenuExtension($helper);
    $app->view()->parserExtensions[] = $menuExtension;

});


$app->hook('slim.before', function() use ($app){

    $menuFactory = new \Knp\Menu\MenuFactory();

    $menu = $menuFactory->createItem('root');
    $menu->setChildrenAttribute('class', 'nav navbar-nav');

    $menu->addChild('Home', array('uri' => '/'));
    $menu->addChild('Posts', array('uri' => '/posts'));
    $menu->addChild('PHPBelfast', array('uri' => 'http:/www.phpbelfast.com/'));
    $menu->addChild('Coming soon');

    $app->view->set('mainMenu', $menu);

});


