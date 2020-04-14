<?php

declare(strict_types=1);

namespace Baraja\Invoice;


use Baraja\Invoice\Entity\Config;
use Baraja\Invoice\Entity\Order;
use Latte\Engine;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;

final class InvoiceGenerator
{

	/** @var string */
	private $tempDir;

	/** @var string */
	private $baseInvoicePdfPath;

	/** @var string|null */
	private $invoiceDirName;

	/** @var Config|null */
	private $config;


	/**
	 * @param string $tempDir
	 * @param string $baseInvoicePdfPath
	 * @param string|null $invoiceDirName
	 */
	public function __construct(string $tempDir, string $baseInvoicePdfPath, ?string $invoiceDirName = null)
	{
		$this->tempDir = rtrim($tempDir, '/') . '/baraja-invoice';
		$this->baseInvoicePdfPath = $baseInvoicePdfPath;
		$this->invoiceDirName = $invoiceDirName === null ? null : trim($invoiceDirName, '/');
	}


	/**
	 * Smart helper for create simple invoice by Order entity.
	 *
	 * @param Order $order
	 * @param int|null $invoiceNumber
	 * @param string|null $templatePath
	 * @return string
	 * @throws \Mpdf\MpdfException
	 */
	public function create(Order $order, ?int $invoiceNumber = null, ?string $templatePath = null): string
	{
		$relativePath = $this->getRelativePath($order);
		$invoicePath = rtrim($this->baseInvoicePdfPath, '/') . '/' . $relativePath;
		$invoiceNumber = $invoiceNumber ?? $order->getVariable();
		$templatePath = $templatePath ?? __DIR__ . '/Template/default.latte';

		$this->createPdf($order, $invoiceNumber, $templatePath, $invoicePath);
		$order->setInvoicePath($relativePath);

		return $invoicePath;
	}


	/**
	 * Generate PDF by Order entity and save to file.
	 * Default font is Roboto.
	 *
	 * @param Order $order
	 * @param int $invoiceNumber
	 * @param string $templatePath
	 * @param string $invoicePath
	 * @throws \Mpdf\MpdfException
	 */
	public function createPdf(Order $order, int $invoiceNumber, string $templatePath, string $invoicePath): void
	{
		FileSystem::createDir($this->tempDir);

		$pdf = new Mpdf([
			'showImageErrors' => true,
			'fontDir' => array_merge((new ConfigVariables)->getDefaults()['fontDir'] ?? [], [
				__DIR__ . '/Fonts',
			]),
			'fontdata' => array_merge((new FontVariables)->getDefaults()['fontdata'] ?? [], [
				'roboto' => [
					'R' => 'Roboto-Regular.ttf',
					'I' => 'Roboto-Italic.ttf',
					'B' => 'Roboto-Bold.ttf',
				],
				'oxygenmono' => [
					'R' => 'OxygenMono-Regular.ttf',
				],
				'librebarcode39extended' => [
					'R' => 'LibreBarcode39Extended-Regular.ttf',
				],
			]),
			'tempDir' => $this->tempDir,
		]);

		$pdf->WriteHTML(
			(new Engine)->renderToString($templatePath, [
				'order' => $order,
				'items' => $order->getInvoiceItems(),
				'invoiceNumber' => $invoiceNumber,
				'config' => $this->config ?? new Config,
			])
		);

		FileSystem::write($invoicePath, (string) $pdf->Output('invoice', 'S'));
	}


	/**
	 * Generate short relative path for invoice.
	 * For example "invoicess/2020-04/20106750-p36ba18czx.pdf"
	 *
	 * @param Order $order
	 * @return string
	 */
	public function getRelativePath(Order $order): string
	{
		return ($this->invoiceDirName ?? 'invoices') . '/' . $order->getInsertedDate()->format('Y-m')
			. '/' . $order->getVariable() . '-' . Random::generate() . '.pdf';
	}


	/**
	 * @param Config $config
	 */
	public function setConfig(Config $config): void
	{
		$this->config = $config;
	}
}