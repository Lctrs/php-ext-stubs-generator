<?php

declare(strict_types=1);

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Cli\GenerateStubsForExtCommand;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Node\GetNodesGroupedByNamespace;
use Lctrs\PhpExtStubsGenerator\Infrastructure\Node\GetNodesUsingBetterReflection;
use PHPStan\BetterReflection\BetterReflection;
use PHPStan\BetterReflection\Reflector\ClassReflector;
use PHPStan\BetterReflection\Reflector\ConstantReflector;
use PHPStan\BetterReflection\Reflector\FunctionReflector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(BetterReflection::class);

    $services->set(ClassReflector::class)
        ->factory([service(BetterReflection::class), 'classReflector']);
    $services->set(FunctionReflector::class)
        ->factory([service(BetterReflection::class), 'functionReflector']);
    $services->set(ConstantReflector::class)
        ->factory([service(BetterReflection::class), 'constantReflector']);

    $services->set(GetNodesUsingBetterReflection::class)
        ->args([
            service(ClassReflector::class),
            service(FunctionReflector::class),
            service(ConstantReflector::class),
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
