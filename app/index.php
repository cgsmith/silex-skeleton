<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

use Silex\Provider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

$app = new \Silex\Application();

/** Register providers */
// Configuration
$app->register(new \Igorw\Silex\ConfigServiceProvider(APP_CONFIG_PATH . APP_ENV .'.json'));

// Pimple DUmper for autohinting
$app->register(new Sorien\Provider\PimpleDumpProvider());

// Below is required for user related stuff - they are used elsewhere as well
$simpleUserProvider = new SimpleUser\UserServiceProvider();
$app->register(new Provider\DoctrineServiceProvider());
$app->register(new Provider\SecurityServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\RememberMeServiceProvider());
$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\SwiftmailerServiceProvider());
$app->register(new Provider\TwigServiceProvider(),['twig.path'=>$app['twig.path'],'twig.templates'=>$app['twig.templates']]);
$app->register($simpleUserProvider);

// i18n
$app->register(new Provider\TranslationServiceProvider());

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new Symfony\Component\Translation\Loader\YamlFileLoader());
    $translator->addResource('yaml', APP_LOCALE_PATH . 'en.yml', 'en');
    $translator->addResource('yaml', APP_LOCALE_PATH . 'fr.yml', 'fr');
    return $translator;
}));

// set current locale
$app['translator']->setLocale((null !== ($app['session']->get('current_language'))) ? $app['session']->get('current_language') : 'en'); // set current language


/** Configurations */
$app['security.firewalls'] = array(
    // the
    'login' => array(
        'pattern' => '^/user/login$',
    ),
    'register' => array(
        'pattern' => '^/user/register$',
    ),
    'forgot-password' => array(
        'pattern' => '^/user/forgot-password$',
    ),
    'secured_area' => array(
        'pattern' => '^.*$',
        'anonymous' => false,
        'remember_me' => array(),
        'form' => array(
            'login_path' => '/user/login',
            'check_path' => '/user/login_check',
        ),
        'logout' => array(
            'logout_path' => '/user/logout',
        ),
        'users' => $app->share(function($app) { return $app['user.manager']; }),
    ),
);

$app['user.options'] = array(
    'templates' => array(
        'layout' => 'layout.twig',
        'register' => 'register.twig',
        'register-confirmation-sent' => 'register-confirmation-sent.twig',
        'login' => 'login.twig',
        'login-confirmation-needed' => 'login-confirmation-needed.twig',
        'forgot-password' => 'forgot-password.twig',
        'reset-password' => 'reset-password.twig',
        'view' => 'view.twig',
        'edit' => 'edit.twig',
        'list' => 'list.twig',
    ),

    'emailConfirmation' => array(
        'required' => true, // Whether to require email confirmation before enabling new accounts.
        'template' => 'email/confirm-email.twig',
    ),

    'passwordReset' => array(
        'template' => 'email/reset-password.twig',
        'tokenTTL' => 86400, // How many seconds the reset token is valid for. Default: 1 day.
    ),

    // Configure the user mailer for sending password reset and email confirmation messages.
    'mailer' => array(
        'fromEmail' => array(
            'address' => 'noreply@email.com',
        ),
    ),
);

/** Define Routes */
$app->get('/', 'App\controller\search::indexAction');
$app->mount('/user', $simpleUserProvider);

/** Example route
$app['controllers_factory']
    ->method('GET|POST')
    ->match('/fragrance/create', 'App\controller\fragrance::createAction');


$app->get('/fragrance/list', 'App\controller\fragrance::listAction')
    ->before(function(Request $request) use ($app) {
        if (!$app['security']->isGranted('ROLE_ADMIN', $request->get('id'))) {
            throw new AccessDeniedException();
        }
    })
    ->bind('fragrance.list');
 */

/** Route to change languages */
$app->get('/lang/{lang}', function($lang) use($app) {
    if (is_file(APP_LOCALE_PATH . $lang . '.yml')) {
        $app['session']->set('current_language', $lang);
    }
    // yea this needs work
     return $app->redirect($app['request']->headers->get('referer'));
});

$app->run();