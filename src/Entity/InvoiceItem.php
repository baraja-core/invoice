<?php

declare(strict_types=1);

namespace Baraja\Invoice\Entity;


interface InvoiceItem
{
	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return int
	 */
	public function getCount(): int;

	/**
	 * @return string
	 */
	public function getPriceFormatted(): string;

	/**
	 * @return string|null
	 */
	public function getSecondLabel(): ?string;
}