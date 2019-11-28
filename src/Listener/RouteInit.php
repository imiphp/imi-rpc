<?php
namespace Imi\Rpc\Listener;

use Imi\Event\Event;
use Imi\ServerManage;
use Imi\RequestContext;
use Imi\Bean\BeanFactory;
use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Rpc\Contract\IRpcServer;
use Imi\Bean\Annotation\Listener;
use Imi\Server\Route\RouteCallable;
use Imi\Bean\Annotation\AnnotationManager;
use Imi\Rpc\Route\Annotation\Parser\RpcControllerParser;

/**
 * RPC 服务器路由初始化
 * @Listener("IMI.MAIN_SERVER.WORKER.START")
 */
class RouteInit implements IEventListener
{
    /**
     * 事件处理方法
     * @param EventParam $e
     * @return void
     */
    public function handle(EventParam $e)
    {
        $this->parseAnnotations($e);
    }

    /**
     * 处理注解路由
     * @return void
     */
    private function parseAnnotations(EventParam $e)
    {
        $controllerParser = RpcControllerParser::getInstance();
        foreach(ServerManage::getServers() as $name => $server)
        {
            if(!$server instanceof IRpcServer)
            {
                continue;
            }
            /** @var IRpcServer $server */
            $controllerAnnotationClass = $server->getControllerAnnotation();
            $actionAnnotationClass = $server->getActionAnnotation();
            $routeAnnotationClass = $server->getRouteAnnotation();
            RequestContext::create();
            RequestContext::set('server', $server);
            /** @var \Imi\Rpc\Route\IRoute $route */
            // $route = $server->getBean('RpcRoute');
            $route = $server->getBean($server->getRouteClass());
            $serverTypeName = $this->getServerTypeName(BeanFactory::getObjectClass($server));
            // $eventName = 'IMI.ROUTE.INIT.DEFAULT:' . $serverTypeName;
            foreach($controllerParser->getByServer($name, $controllerAnnotationClass) as $className => $classItem)
            {
                $classAnnotation = $classItem->getAnnotation();
                foreach(AnnotationManager::getMethodsAnnotations($className, $actionAnnotationClass) as $methodName => $actionAnnotations)
                {
                    /** @var \Imi\Rpc\Route\Annotation\Contract\IRpcRoute[] $routes */
                    $routes = AnnotationManager::getMethodAnnotations($className, $methodName, $routeAnnotationClass);
                    if(!isset($routes[0]))
                    {
                        // $data = compact('className', 'classAnnotation', 'methodName');
                        // $result = null;
                        // $data['result'] = &$result;
                        // Event::trigger($eventName, $data);
                        // if(null !== $result)
                        // {
                        //     $routes = [
                        //         $result
                        //     ];
                        // }
                        $routes = [
                            $route->getDefaultRouteAnnotation($className, $methodName, $classAnnotation),
                        ];
                    }
                    
                    foreach($routes as $routeItem)
                    {
                        $route->addRuleAnnotation($classAnnotation, $routeItem, new RouteCallable($server, $className, $methodName), [
                            'serverName'    =>  $name,
                        ]);
                    }
                }
            }
            RequestContext::destroy();
        }
    }

    /**
     * 获取服务器类型名称
     *
     * @param string $className
     * @return string|boolean
     */
    private function getServerTypeName($className)
    {
        if(\preg_match('/Imi\\\\Server\\\\([^\\\\]+)\\\\Server/', $className, $matches) > 0)
        {
            return $matches[1];
        }
        else
        {
            return false;
        }
    }
}