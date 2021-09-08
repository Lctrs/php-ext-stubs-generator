<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Infrastructure\Node;

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use Lctrs\PhpExtStubsGenerator\Application\Node\Nodes;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use RuntimeException;

use function array_key_exists;
use function array_values;
use function count;
use function get_debug_type;
use function property_exists;
use function Safe\ksort;
use function Safe\sprintf;

final class GetNodesGroupedByNamespace implements GetNodes
{
    private GetNodes $getNodes;

    public function __construct(GetNodes $getNodes)
    {
        $this->getNodes = $getNodes;
    }

    public function forExtension(string $name): Nodes
    {
        $nodes = $this->getNodes->forExtension($name);

        return new Nodes(
            $this->groupByNamespace($nodes->classes()),
            $this->groupByNamespace($nodes->functions()),
            $this->groupByNamespace($nodes->constants()),
        );
    }

    /**
     * @param list<T> $statements
     *
     * @return list<Namespace_|T>
     *
     * @template T of Stmt
     */
    private function groupByNamespace(array $statements): array
    {
        $namespaces = [];
        foreach ($statements as $statement) {
            if (! property_exists($statement, 'namespacedName')) {
                $namespace = $namespaces['']
                    ?? ($namespaces[''] = new Namespace_());

                $namespace->stmts[] = $statement;

                continue;
            }

            if (! $statement->namespacedName instanceof Name) {
                throw new RuntimeException(sprintf(
                    'Unsupported namespacedName property type. Expected one of "%s", "%s" given.',
                    Name::class,
                    get_debug_type($statement->namespacedName)
                ));
            }

            $namespaceName = $statement->namespacedName->slice(0, -1);
            $key           = $namespaceName === null
                ? ''
                : $namespaceName->toLowerString();

            $namespace = $namespaces[$key]
                ?? ($namespaces[$key] = new Namespace_($namespaceName));

            $namespace->stmts[] = $statement;
        }

        if (array_key_exists('', $namespaces) && count($namespaces) === 1) {
            return $statements;
        }

        ksort($namespaces);

        return array_values($namespaces);
    }
}
