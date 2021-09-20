<?php
namespace HK\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use HK\CoreBundle\Configuration\Configuration;

class LocaleListener implements EventSubscriberInterface{
    private $defaultLocale;
    /**
     * 
     * @param string $default
     */
    public function __construct($default = 'vi'){
        $this->defaultLocale = $default;
    }
    
    public function onKernelRequest(GetResponseEvent $event){
        $request = $event->getRequest();
        $request->setLocale(Configuration::instance()->getCurrentLang());
    }
    public static function getSubscribedEvents()
    {
        return array(
                // must be registered after the default Locale listener
                KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}