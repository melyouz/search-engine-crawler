<?php


namespace App\Hits\Presentation\Api\Controller;


use App\Hits\Application\Service\ListService;
use App\Hits\Application\Service\SearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController
{
    private RequestStack $requestStack;
    private ListService $listService;
    private SearchService $searchService;
    private SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, SearchService $searchService, ListService $listService, SerializerInterface $serializer)
    {
        $this->requestStack = $requestStack;
        $this->listService = $listService;
        $this->searchService = $searchService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/{searchEngineName}", name="api_search")
     */
    public function __invoke(string $searchEngineName)
    {
        $request = $this->requestStack->getCurrentRequest();
        $searchTerm = $request->query->get('s', null);

        if (!$searchTerm) {
            return new JsonResponse(['message' => 'Please, specify a search term.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->searchService->search($searchEngineName, $searchTerm);

        $data = $this->listService->list($searchEngineName);

        return new Response($this->serializer->serialize($data, 'json'));
    }
}