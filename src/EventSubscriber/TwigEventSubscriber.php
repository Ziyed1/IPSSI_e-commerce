<?php

namespace App\EventSubscriber;

use App\Repository\HeaderRepository;
use App\Repository\PageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $pagesRepository;
    private $headersRepository;

    public function __construct(Environment $twig, PageRepository $pagesRepository, HeaderRepository $headersRepository)
    {
        $this->twig = $twig;
        $this->pagesRepository = $pagesRepository;
        $this->headersRepository = $headersRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // ...
        $this->twig->addGlobal('pages', $this->pagesRepository->findAll());
        $this->twig->addGlobal('headers', $this->headersRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }

}
