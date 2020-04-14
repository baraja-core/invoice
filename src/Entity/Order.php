<?php

declare(strict_types=1);

namespace Baraja\Invoice\Entity;


interface Order
{
	/**
	 * @return int
	 */
	public function getVariable(): int;

	/**
	 * @return InvoiceItem[]
	 */
	public function getInvoiceItems(): array;

	/**
	 * @return \DateTime
	 */
	public function getInsertedDate(): \DateTime;

	/**
	 * @param string $relativePath
	 */
	public function setInvoicePath(string $relativePath): void;

	/**
	 * @return string
	 */
	public function getPriceFormatted(): string;

	/**
	 * @return string
	 */
	public function getSupplierAddress(): string;

	/**
	 * @return string
	 */
	public function getSubscriberAddress(): string;

	/**
	 * @return int
	 */
	public function getDueDateCountDays(): int;
}