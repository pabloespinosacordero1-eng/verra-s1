<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MainApiControllerTest extends WebTestCase
{
    public function testEndpointReturnsBadRequestOnEmptyBody(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/execute', [], [], ['CONTENT_TYPE' => 'application/json'], '');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
