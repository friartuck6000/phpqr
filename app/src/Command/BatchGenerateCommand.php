<?php

namespace Anthem\QrGenerator\Command;

use Anthem\QrGenerator\Generator\QrGeneratorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class BatchGenerateCommand extends Command
{
	protected static $defaultName = 'generate:batch';

	public function __construct(protected string $outputPath, string $name = null)
	{
		parent::__construct($name);
	}

	protected function configure(): void
	{
		$this->setDescription('Generate QR codes for multiple entries in an input file')
			->addArgument('file', InputArgument::REQUIRED, 'Path to a YAML input file');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$file = $input->getArgument('file');
		$filePath = realpath($file);

		if (!is_file($filePath)) {
			throw new \RuntimeException('Invalid file path');
		}

		$parsed = Yaml::parseFile($filePath);
		foreach ($parsed as $element) {
			$output->writeln("Generating code for \"<comment>{$element['label']}</comment>\"");
			$code = QrGeneratorFactory::buildPng($element['url'], $element['label']);

			$code->saveToFile($this->outputPath.'/'.$element['filename'].'.png');
		}

		return Command::SUCCESS;
	}
}