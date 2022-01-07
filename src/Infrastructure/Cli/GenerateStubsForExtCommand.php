<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Infrastructure\Cli;

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function assert;
use function is_dir;
use function is_string;
use function rtrim;
use function Safe\file_put_contents;
use function Safe\mkdir;

final class GenerateStubsForExtCommand extends Command
{
    private GetNodes $getNodes;
    private Standard $printer;

    public function __construct(GetNodes $getNodes)
    {
        parent::__construct();

        $this->getNodes = $getNodes;
        $this->printer  = new Standard();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'ext',
                InputArgument::REQUIRED,
                'The name of the PHP extension to generate stubs for',
            )
            ->addOption(
                'target',
                null,
                InputOption::VALUE_REQUIRED,
                '',
                'stubs/'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ext = $input->getArgument('ext');
        assert(is_string($ext));
        $target = $input->getOption('target');
        assert(is_string($target));

        $target = rtrim($target, '/') . '/';

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $nodes = $this->getNodes->forExtension($ext);

        $this->stubClasses($nodes->classes(), $target, $ext);
        $this->stubFunctions($nodes->functions(), $target, $ext);
        $this->stubConstants($nodes->constants(), $target, $ext);

        return 0;
    }

    /**
     * @param list<Namespace_|ClassLike> $classes
     */
    private function stubClasses(array $classes, string $target, string $extension): void
    {
        if ($classes === []) {
            return;
        }

        file_put_contents(
            $target . $extension . '_c.php',
            $this->printer->prettyPrintFile($classes)
        );
    }

    /**
     * @param list<Namespace_|Function_> $functions
     */
    private function stubFunctions(array $functions, string $target, string $extension): void
    {
        if ($functions === []) {
            return;
        }

        file_put_contents(
            $target . $extension . '.php',
            $this->printer->prettyPrintFile($functions)
        );
    }

    /**
     * @param list<Namespace_|Const_|Expression> $constants
     */
    private function stubConstants(array $constants, string $target, string $extension): void
    {
        if ($constants === []) {
            return;
        }

        file_put_contents(
            $target . $extension . '_d.php',
            $this->printer->prettyPrintFile($constants)
        );
    }
}
