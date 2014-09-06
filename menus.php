<?php

// use a hook to init the menu provider
$app->hook('slim.before', function() use ($app) {
    $menuOptions = array(
        'currentClass' => 'active'
    );
    $menuProvider = new \PhpBelfast\Menu\Provider($app->container, $app->request, $menuOptions);

    // use the twig extension in the view
    $app->view->parserExtensions[] = $menuProvider->getTwigExtension();
});


// start building a menu
$menuFactory = new \Knp\Menu\MenuFactory();

// create a new menu with a root node
$mainMenu = $menuFactory->createItem('root', array(
    'childrenAttributes' => array(
        'class' => 'nav navbar-nav'
    )
));

// simple link node
$mainMenu->addChild('Home', array(
    'uri' => $app->urlFor('home') // \Slim\Route
));

// node with children
$mainMenu->addChild('Posts', array(
    'uri' => '#',
    'linkAttributes' => array( // attributes for bootstrap
        'class' => 'dropdown-toggle',
        'data-toggle' => 'dropdown'
    ),
    'childrenAttributes' => array(
        'class' => 'dropdown-menu'
    )
));

// add child nodes from the post repository
$posts = $app->postRepo->getAll();
foreach ($posts as $post) {
    $mainMenu['Posts']->addChild($post->title, array(
        'uri' => $app->urlFor('posts.item', array('id' => $post->id))
    ));
}

// external link
$mainMenu->addChild('PHPBelfast', array(
    'uri' => 'http://www.phpbelfast.com/'
));

// link without
$mainMenu->addChild('Coming soon');

// pass it to the view
$app->view->set('mainMenu', $mainMenu);


