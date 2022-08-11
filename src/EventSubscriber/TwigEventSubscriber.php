<?php

namespace App\EventSubscriber;

use App\Repository\PageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $pagesRepository;

    public function __construct(Environment $twig, PageRepository $pagesRepository)
    {
        $this->twig = $twig;
        $this->pagesRepository = $pagesRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // ...
        $this->twig->addGlobal('pages', $this->pagesRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }

}
