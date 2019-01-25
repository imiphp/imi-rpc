<?php
namespace Imi\Rpc\Listener;

use Imi\ServerManage;
use Imi\RequestContext;
use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Bean\Annotation\Listener;
use Imi\Server\Route\RouteCallable;
use Imi\Bean\Annotation\AnnotationManager;
use Imi\Rpc\Route\Annotation\RpcRoute;
use Imi\Rpc\Route\Annotation\RpcAction;
use Imi\Rpc\Route\Annotation\Parser\RpcControllerParser;
use Imi\Rpc\BaseRpcServer;

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
            if(!$server instanceof BaseRpcServer)
            {
                continue;
            }
            RequestContext::create();
            RequestContext::set('server', $server);
            $route = $server->getBean('RpcRoute');
            foreach($controllerParser->getByServer($name) as $className => $classItem)
            {
                $classAnnotation = $classItem['annotation'];
                foreach(AnnotationManager::getMethodsAnnotations($className, RpcAction::class) as $methodName => $actionAnnotations)
                {
                    $routes = AnnotationManager::getMethodAnnotations($className, $methodName, RpcRoute::class);
                    if(!isset($routes[0]))
                    {
                        $routes = [
                            new RpcRoute([
                                'name' => $methodName,
                            ])
                        ];
                    }
                    
                    foreach($routes as $routeItem)
                    {
                        $route->addRuleAnnotation($routeItem, new RouteCallable($className, $methodName), [
                            'serverName'    =>  $name,
                            'controller'    =>  $classAnnotation,
                        ]);
                    }
                }
            }
            RequestContext::destroy();
        }
    }

}