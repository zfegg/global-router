# Zend Framework Global Router - ZF公共路由

ZendMvc 自带的 `Zend\Mvc\ModuleRouteListener` 不方便, 每新的 `Controller` 都需要在 `ControllerManager` 配置下.
`Zfegg\GlobalRouter\GlobalModuleRouteListener`

## Installation - 安装

```
composer require zfegg/global-router
```

## Usage - 使用举例

首先, 在 `config/modules.config.php` 中添加模块加载.

```
return [
    //... Your modules
    'Zfegg\\GlobalRouter'
];
```

默认路由方式: `/module/controller/action/param1/value1/param2/value2/...` , 类似ZF1默认路由

