<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Application\Node;

interface GetNodes
{
    public function forExtension(string $name): Nodes;
}
