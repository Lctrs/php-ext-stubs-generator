<?php

declare(strict_types=1);

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Cli\GenerateStubsForExtCommand;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Node\GetNodesGroupedByNamespace;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Node\GetNodesUsingBetterReflection;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(BetterReflection::class);

    $services->set(DefaultReflector::class)
        ->factory([service(BetterReflection::class), 'reflector']);
    $services->alias(Reflector::class, DefaultReflector::class);

    $services->set(GetNodesUsingBetterReflection::class)
        ->args([
            service(Reflector::class),
        ]);
    $services->set(GetNodesGroupedByNamespace::class)
        ->args([
            service(GetNodesUsingBetterReflection::class),
        ]);
    $services->alias(GetNodes::class, GetNodesGroupedByNamespace::class);

    $services->set(GenerateStubsForExtCommand::class)
        ->args([
            service(GetNodes::class),
        ])
        ->public();
};
