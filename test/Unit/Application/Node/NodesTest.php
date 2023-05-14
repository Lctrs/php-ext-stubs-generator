<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Test\Unit\Application\Node;

use Lctrs\PhpExtStubsGenerator\Application\Node\Nodes;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;

final class NodesTest extends TestCase
{
    public function testItReturnsClasses(): void
    {
        $classes = [new Class_('Foo')];

        self::assertSame(
            $classes,
            (new Nodes($classes, [], []))->classes(),
        );
    }

    public function testItReturnsFunctions(): void
    {
        $functions = [new Function_('foo')];

        self::assertSame(
            $functions,
            (new Nodes([], $functions, []))->functions(),
        );
    }

    public function testItReturnsConstants(): void
    {
        $constants = [
            new Const_([
                new \PhpParser\Node\Const_('FOO', new String_('foo')),
            ]),
        ];

        self::assertSame(
            $constants,
            (new Nodes([], [], $constants))->constants(),
        );
    }
}
