<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class MainApiController extends AbstractController
{
    #[Route('/api/v1/execute', name: 'api_execute', methods: ['POST'])]
    public function handle(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse(['error' => 'Empty body'], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['status' => 'success', 'data' => []], Response::HTTP_OK);
    }
}
