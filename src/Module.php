<?php

namespace Zfegg\GlobalRouter;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $gListener    = new GlobalModuleRouteListener();
        $gListener->attach($eventManager);
    }

    public function getConfig()
    {
        return GlobalModuleRouteListener::getDefaultRouterConfig();
    }
}
