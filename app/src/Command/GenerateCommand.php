<?php

namespace Anthem\QrGenerator\Command;

use Anthem\QrGenerator\Generator\QrGeneratorFactory;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\SvgWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
	protected static $defaultName = 'generate';

	public function __construct(protected string $outputPath, string $name = null)
	{
		parent::__construct($name);
	}

	protected function configure(): void
	{
		$this->setDescription('Generate a QR code')
			->addArgument('url', InputArgument::REQUIRED, 'The URL to encode')
			->addArgument('filename', InputArgument::OPTIONAL, 'Output filename; defaults to timestamp')
			->addOption('label', null, InputOption::VALUE_REQUIRED, 'Optional code label');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$url = $input->getArgument('url');
		$filename = $input->getArgument('filename') ?? (string) time();
		$label = $input->getOption('label') ?? null;

		$result = QrGeneratorFactory::buildSvg($url, $label);

		echo $result->saveToFile($this->outputPath.'/'.$filename.'.svg');

		return Command::SUCCESS;
	}
}