<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
//use Zend\View\Resolver\TemplatePathStack;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //$eventManager->attach(MvcEvent::EVENT_DISPATCH,array($this,'changeLayout'),100);
    }
    
    public function init(ModuleManager $manager)
    {
        $events = $manager->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            /*
             if (get_class($controller) == 'Admin\Controller\IndexController')         {
             $controller->layout('layout/admin');
             }
            */
            $controller->layout('layout/layout');
    
            $isMobile = true;
    
            if($isMobile) {
                $controller->layout('layout/mobile');
            }
    
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /*
     * change layout
     */
    public function changeLayout(MvcEvent $event)
    {
        $isMobile = true;
    
        if($isMobile) {
            $services = $event->getApplication()->getServiceManager();
            $config = $services->get('config');
            $stack = $services->get('ViewTemplatePathStack');
            $stack->addPaths($config['mobile']['template_path_stack']);
    
            $layout = $event->getViewModel();
            $layout->setTemplate('layout/mobile');
        }
    }
}
