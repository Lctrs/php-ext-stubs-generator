<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Infrastructure\Node;

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use Lctrs\PhpExtStubsGenerator\Application\Node\Nodes;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\BetterReflection\Reflector\ClassReflector;
use PHPStan\BetterReflection\Reflector\ConstantReflector;
use PHPStan\BetterReflection\Reflector\FunctionReflector;
use ReflectionExtension;

use function array_keys;
use function array_map;

final class GetNodesUsingBetterReflection implements GetNodes
{
    private ClassReflector $classReflector;
    private FunctionReflector $functionReflector;
    private ConstantReflector $constantReflector;

    public function __construct(
        ClassReflector $classReflector,
        FunctionReflector $functionReflector,
        ConstantReflector $constantReflector
    ) {
        $this->classReflector    = $classReflector;
        $this->functionReflector = $functionReflector;
        $this->constantReflector = $constantReflector;
    }

    public function forExtension(string $name): Nodes
    {
        $extension = new ReflectionExtension($name);

        return new Nodes(
            array_map(
                fn (
                    string $className
                ): Stmt\ClassLike => $this->classReflector->reflect($className)->getAst(),
                array_keys($extension->getClasses())
            ),
            array_map(
                fn (
                    string $functionName
                ): Node\FunctionLike => $this->functionReflector->reflect($functionName)->getAst(),
                array_keys($extension->getFunctions())
            ),
            array_map(
                function (string $constantName): Const_|Expression {
                    $ast = $this->constantReflector->reflect($constantName)->getAst();

                    if ($ast instanceof Stmt) {
                        return $ast;
                    }

                    return new Expression($ast, $ast->getAttributes());
                },
                array_keys($extension->getConstants())
            ),
        );
    }
}
