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
    /** @var list<Namespace_|ClassLike> */
    private array $classes;
    /** @var list<Namespace_|Function_> */
    private array $functions;
    /** @var list<Namespace_|Const_|Expression> */
    private array $constants;

    /**
     * @param list<Namespace_|ClassLike>         $classes
     * @param list<Namespace_|Function_>         $functions
     * @param list<Namespace_|Const_|Expression> $constants
     */
    public function __construct(array $classes, array $functions, array $constants)
    {
        $this->classes   = $classes;
        $this->functions = $functions;
        $this->constants = $constants;
    }

    /**
     * @return list<Namespace_|ClassLike>
     */
    public function classes(): array
    {
        return $this->classes;
    }

    /**
     * @return list<Namespace_|Function_>
     */
    public function functions(): array
    {
        return $this->functions;
    }

    /**
     * @return list<Namespace_|Const_|Expression>
     */
    public function constants(): array
    {
        return $this->constants;
    }
}
