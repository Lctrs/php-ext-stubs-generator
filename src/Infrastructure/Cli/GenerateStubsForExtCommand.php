<?php

declare(strict_types=1);

namespace Lctrs\PhpExtStubsGenerator\Infrastructure\Cli;

use Lctrs\PhpExtStubsGenerator\Application\Node\GetNodes;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function is_dir;
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
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        if ($input->getOption('target') !== null) {
            return;
        }

        $input->setOption('target', 'stubs/');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ext    = $input->getArgument('ext');
        $target = rtrim($input->getOption('target'), '/') . '/';

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $nodes = $this->getNodes->forExtension($ext);

        $this->stubConstants($nodes->constants(), $target, $ext);
        $this->stubFunctions($nodes->functions(), $target, $ext);
        $this->stubClasses($nodes->classes(), $target, $ext);

        return 0;
    }

    /**
     * @param list<Const_|Expression> $constants
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

    /**
     * @param list<FunctionLike> $functions
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
     * @param list<ClassLike> $classes
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
}
