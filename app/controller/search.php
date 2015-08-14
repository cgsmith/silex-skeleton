<?php
namespace app\controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Search
{
    public function indexAction(Request $request, Application $app) {
        return $app['twig']->render($app['twig.templates']['search']['index'], array(
            'layout_template' => $app['twig.templates']['layout'],
        ));
    }
}