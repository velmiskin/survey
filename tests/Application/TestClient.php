<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Component\User\Infrastructure\Doctrine\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

final class TestClient
{
    private ?string $uri = null;
    private array $headers = [];

    public function __construct(
        private readonly KernelBrowser $browser,
        private readonly RouterInterface $router,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function url(string $routeName, array $parameters = []): self
    {
        $this->uri = $this->router->generate($routeName, $parameters);

        return $this;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function withToken(string $token): self
    {
        $this->headers['HTTP_AUTHORIZATION'] = 'Bearer '.$token;

        return $this;
    }

    public function actingAs(User $user): self
    {
        $token = $this->jwtManager->create($user);
        $this->browser->setServerParameter('HTTP_AUTHORIZATION', 'Bearer '.$token);

        return $this;
    }

    public function get(): self
    {
        $this->request('GET');

        return $this;
    }

    private function request(string $method, array $data = []): void
    {
        $content = !empty($data) ? json_encode($data, JSON_THROW_ON_ERROR) : null;

        $this->browser->request($method, $this->uri, [], [], $this->headers, $content);

        // Reset for next request
        $this->uri = null;
        $this->headers = [];
    }

    public function post(array $data = []): self
    {
        $this->request('POST', $data);

        return $this;
    }

    public function put(array $data = []): self
    {
        $this->request('PUT', $data);

        return $this;
    }

    public function patch(array $data = []): self
    {
        $this->request('PATCH', $data);

        return $this;
    }

    public function delete(): self
    {
        $this->request('DELETE');

        return $this;
    }

    public function decode(): array
    {
        return json_decode(
            $this->browser->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    public function statusCode(): int
    {
        return $this->browser->getResponse()->getStatusCode();
    }
}
