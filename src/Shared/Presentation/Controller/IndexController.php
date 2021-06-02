<?php


namespace App\Shared\Presentation\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="app_index", methods={"GET"})
     * @Route("/{route}", name="app_fallback", methods={"HEAD", "GET"}, requirements={"route"="^(?!.*api|_wdt|_profiler).+"})
     */
    public function __invoke(): Response
    {
        $content = $this->twig->render('index.html.twig');

        return new Response($content);
    }
}