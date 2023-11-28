<?php

namespace Anthem\QrGenerator\Generator;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QrGeneratorFactory
{
	public static function buildSvg(string $url, ?string $label = null): ResultInterface
	{
		return self::createBaseBuilder($url, $label)
			->writer(new SvgWriter())
			->writerOptions([])
			->size(300)
			->margin(10)
			->labelFont(new NotoSans(12))
			->build();
		
	}

	public static function buildPng(string $url, ?string $label = null): ResultInterface
	{
		return self::createBaseBuilder($url, $label)
			->writer(new PngWriter())
			->writerOptions([])
			->size(1200)
			->margin(40)
			->labelFont(new NotoSans(72))
			->build();
	}

	protected static function createBaseBuilder(string $url, ?string $label = null): BuilderInterface
	{
		$builder = Builder::create()
			->data($url)
			->encoding(new Encoding('UTF-8'))
			->errorCorrectionLevel(new ErrorCorrectionLevelLow())
			->margin(10)
			->roundBlockSizeMode(new RoundBlockSizeModeMargin());

		if ($label) {
			$builder->labelText($label)
				->labelAlignment(new LabelAlignmentCenter());
		}

		return $builder;
	}

}
