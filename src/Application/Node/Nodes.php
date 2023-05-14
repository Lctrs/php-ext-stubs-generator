<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Application\Node;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;

final class Nodes
{
    /**
     * @param list<Namespace_|ClassLike>         $classes
     * @param list<Namespace_|Function_>         $functions
     * @param list<Namespace_|Const_|Expression> $constants
     */
    public function __construct(private array $classes, private array $functions, private array $constants)
    {
    }

    /** @return list<Namespace_|ClassLike> */
    public function classes(): array
    {
        return $this->classes;
    }

    /** @return list<Namespace_|Function_> */
    public function functions(): array
    {
        return $this->functions;
    }

    /** @return list<Namespace_|Const_|Expression> */
    public function constants(): array
    {
        return $this->constants;
    }
}
