# Zend Framework Global Router - ZF公共路由


ZF2 自带的 `Zend\Mvc\ModuleRouteListener` 不方便, 每新的 `Controller` 都需要在 `ControllerManager` 配置下.
`Gzfextra\Router\GlobalModuleRouteListener`

### Example - 使用举例

在 `config/application.config.php` 中添加模块加载.

```
return array(
    'modules' => array(
        //... Your modules
	    'Zfegg/GlobalRouter'
    ),
);
```

或者在自己的模块添加以下监听和配置:

```php
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
```

默认路由方式: `/module/controller/action/param1/value1/param2/value2/...` , 类似ZF1默认路由

