<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\RouterInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class ApplicationTestCase extends WebTestCase
{
    use ResetDatabase;

    private ?KernelBrowser $browser = null;
    private ?TestClient $testClient = null;

    protected function client(): TestClient
    {
        if (null === $this->browser) {
            $this->browser = self::createClient();
            $this->browser->setServerParameter('CONTENT_TYPE', 'application/json');
            $this->browser->setServerParameter('HTTP_ACCEPT', 'application/json');
        }

        if (null === $this->testClient) {
            $container = self::getContainer();

            $router = $container->get(RouterInterface::class);
            $jwtManager = $container->get(JWTTokenManagerInterface::class);

            $this->testClient = new TestClient($this->browser, $router, $jwtManager);
        }

        return $this->testClient;
    }
}
