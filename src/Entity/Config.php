<?php

declare(strict_types=1);

namespace Baraja\Invoice\Entity;


final class Config
{
	public const TRANSLATION_KEYS = [
		'invoiceNumber', 'supplier', 'subscriber', 'accountNumber', 'variable', 'dateOfIssue', 'dueDate', 'itemLabel', 'itemLabelSecond', 'itemLabelCount', 'itemLabelPrice', 'finalPrice',
	];

	/** @var string|null */
	private $logo;

	/** @var string[] */
	private $translation;

	/** @var string|null */
	private $footerVatMessage;

	/** @var string|null */
	private $footerMessage;


	/**
	 * @param string|null $logo
	 * @param string[] $translation
	 * @param string|null $footerVatMessage
	 * @param string|null $footerMessage
	 */
	public function __construct(?string $logo = null, array $translation = [], ?string $footerVatMessage = null, ?string $footerMessage = null)
	{
		if ($translation === []) { // default messages
			$translation = [
				'invoiceNumber' => 'Číslo faktury:',
				'supplier' => 'Dodavatel:',
				'subscriber' => 'Odběratel:',
				'accountNumber' => 'Číslo účtu:',
				'variable' => 'Var. symbol:',
				'dateOfIssue' => 'Datum vystavení:',
				'dueDate' => 'Datum splatnosti:',
				'itemLabel' => 'Fakturujeme vám:',
				'itemLabelSecond' => 'Přístup na:',
				'itemLabelCount' => 'Počet:',
				'itemLabelPrice' => 'Celkem:',
				'finalPrice' => 'Celkem:',
			];
		}

		foreach (self::TRANSLATION_KEYS as $translationKey) {
			if (isset($translation[$translationKey]) === false) {
				throw new \RuntimeException('Translation key "' . $translationKey . '" does not exist.');
			}
		}

		$this->logo = $logo;
		$this->translation = $translation;
		$this->footerVatMessage = $footerVatMessage;
		$this->footerMessage = $footerMessage;
	}


	/**
	 * @return string|null
	 */
	public function getLogo(): ?string
	{
		return $this->logo;
	}


	/**
	 * @return string[]
	 */
	public function getTranslation(): array
	{
		return $this->translation;
	}


	/**
	 * @return string|null
	 */
	public function getFooterVatMessage(): ?string
	{
		return $this->footerVatMessage;
	}


	/**
	 * @return string|null
	 */
	public function getFooterMessage(): ?string
	{
		return $this->footerMessage;
	}
}