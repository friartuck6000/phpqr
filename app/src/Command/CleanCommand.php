<?php

namespace Anthem\QrGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends Command
{
	protected static $defaultName = 'clean';

	public function __construct(protected string $outputPath, string $name = null)
	{
		parent::__construct($name);
	}

	protected function configure(): void
	{
		$this->setDescription('Delete the contents of the output directory');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		foreach (glob($this->outputPath.'/*') as $file) {
			if (is_file($file)) {
				$niceFile = str_replace($this->outputPath.'/', '', $file);
				$output->writeln("Removing file <comment>{$niceFile}</comment>");
				unlink($file);
			}
		}
		
		return Command::SUCCESS;
	}
}