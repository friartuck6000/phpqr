<?php

namespace Anthem\QrGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LinkCleanCommand extends Command
{
	protected static $defaultName = 'link:clean';

	protected function configure(): void
	{
		$this->setDescription('Sanitize ugly links')
			->addArgument('url', InputArgument::REQUIRED, 'The URL to sanitize')
			->addOption('amazon', null, InputOption::VALUE_NONE, 'The link given is an Amazon link');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$url = $input->getArgument('url');
		$sanitized = $url;

		if ($input->getOption('amazon')) {
			$sanitized = preg_replace('#^.+(/dp/[^/]+/).+$#', 'https://amazon.com$1', $url);
		}

		$output->writeln($sanitized);
		
		return Command::SUCCESS;
	}
}