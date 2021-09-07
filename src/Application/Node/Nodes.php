<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Application\Node;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;

final class Nodes
{
    /** @var list<ClassLike> */
    private array $classes;
    /** @var list<FunctionLike> */
    private array $functions;
    /** @var list<Const_|Expression> */
    private array $constants;

    /**
     * @param list<ClassLike>         $classes
     * @param list<FunctionLike>      $functions
     * @param list<Const_|Expression> $constants
     */
    public function __construct(array $classes, array $functions, array $constants)
    {
        $this->classes   = $classes;
        $this->functions = $functions;
        $this->constants = $constants;
    }

    /**
     * @return list<ClassLike>
     */
    public function classes(): array
    {
        return $this->classes;
    }

    /**
     * @return list<FunctionLike>
     */
    public function functions(): array
    {
        return $this->functions;
    }

    /**
     * @return list<Const_|Expression>
     */
    public function constants(): array
    {
        return $this->constants;
    }
}
