<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\PreferenceAggregationRequest;
use App\Domain\Model\RecordCollection;
use App\Domain\Service\PreferenceAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class PreferenceAggregationController extends AbstractController
{
    #[Route('/api/preferences/aggregate', name: 'api_preferences_aggregate', methods: ['POST'])]
    public function aggregate(
        Request $request,
        SerializerInterface $serializer,
        PreferenceAggregator $aggregator
    ): JsonResponse {
        $jsonContent = $request->getContent();

        if (empty($jsonContent)) {
            return new JsonResponse(['error' => 'Payload body cannot be empty.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            /** @var PreferenceAggregationRequest $aggregationRequest */
            $aggregationRequest = $serializer->deserialize($jsonContent, PreferenceAggregationRequest::class, 'json');

            // 1. Envolvemos los DTOs crudos en la Colección de Dominio
            $recordCollection = new RecordCollection($aggregationRequest->records);

            // 2. El servicio opera y nos devuelve otra Colección rica de Dominio
            $profileCollection = $aggregator->aggregate($recordCollection);

            // Gracias a JsonSerializable, pasamos el objeto directamente a la respuesta
            return new JsonResponse($profileCollection, Response::HTTP_OK);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An error occurred during request processing.',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
