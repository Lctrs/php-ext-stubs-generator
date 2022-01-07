<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Infrastructure\Node;

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use Lctrs\PhpExtStubsGenerator\Application\Node\Nodes;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use ReflectionExtension;
use Roave\BetterReflection\Reflector\Reflector;

use function array_keys;
use function array_map;
use function assert;

final class GetNodesUsingBetterReflection implements GetNodes
{
    private Reflector $reflector;

    public function __construct(Reflector $reflector)
    {
        $this->reflector = $reflector;
    }

    public function forExtension(string $name): Nodes
    {
        $extension = new ReflectionExtension($name);

        return new Nodes(
            array_map(
                fn (
                    string $className
                ): ClassLike => $this->reflector->reflectClass($className)->getAst(),
                array_keys($extension->getClasses())
            ),
            array_map(
                function (string $functionName): Function_ {
                    $ast = $this->reflector->reflectFunction($functionName)->getAst();

                    assert($ast instanceof Function_);

                    return $ast;
                },
                array_keys($extension->getFunctions())
            ),
            array_map(
                function (string $constantName): Const_|Expression {
                    $ast = $this->reflector->reflectConstant($constantName)->getAst();

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
