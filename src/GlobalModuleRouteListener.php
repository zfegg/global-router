<?php

namespace Zfegg\GlobalRouter;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class GlobalModuleRouteListener
 * @package Zfegg\GlobalRouter
 * @author Xiemaomao
 * @version $Id$
 */
class GlobalModuleRouteListener extends AbstractListenerAggregate
{
    const ORIGINAL_CONTROLLER = '__CONTROLLER__';

    public static function getDefaultRouterConfig($routeName = 'module')
    {
        return [
            'router' => [
                'routes' => [
                    $routeName => [
                        'type'         => 'segment',
                        'options'      => [
                            'route'       => '/:module[/][:controller[/:action]]',
                            'constraints' => [
                                'module'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults'    => [
                                'controller' => 'index',
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'params' => [
                                'type' => 'Wildcard',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
    }

    /**
     * Global Route /module/controller/action
     * @param MvcEvent $e
     */
    public function onRoute(MvcEvent $e)
    {
        $matches    = $e->getRouteMatch();
        $module     = $matches->getParam('module');
        $controller = $matches->getParam('controller');
        if ($module && $controller && strpos($controller, '\\') === false) {
            //ZF2 ModuleRouteListener::ORIGINAL_CONTROLLER
            $matches->setParam(self::ORIGINAL_CONTROLLER, $controller);

            /** @var \Zend\Mvc\Controller\ControllerManager $controllerLoader */
            $controllerLoader = $e->getApplication()->getServiceManager()->get('ControllerLoader');
            $ctrlClass = $this->convertName($module) . '\\Controller\\';
            $ctrlClass .= $this->convertName($controller);
            $controller = $ctrlClass;
            $matches->setParam('controller', $controller);
            $ctrlClass .= 'Controller';
            if (!$controllerLoader->has($controller) && class_exists($ctrlClass)) {
                $controllerLoader->setInvokableClass($controller, $ctrlClass);
                $e->setController($controller);
                $e->setControllerClass($ctrlClass);
            }
        }
    }

    private static function convertName($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }
}
