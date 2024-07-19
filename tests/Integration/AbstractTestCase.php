<?php

declare(strict_types=1);


namespace App\Tests\Integration;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class AbstractTestCase extends KernelTestCase
{
    use InteractsWithMessenger;

    protected readonly ContainerInterface $container;
    protected readonly MessageBusInterface $commandBus;
    protected readonly DatabaseToolCollection $databaseTool;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        self::bootKernel();

        $this->container = static::getContainer();
        $this->commandBus = $this->container->get(MessageBusInterface::class);
        $this->databaseTool = $this->container->get(DatabaseToolCollection::class);
    }

}