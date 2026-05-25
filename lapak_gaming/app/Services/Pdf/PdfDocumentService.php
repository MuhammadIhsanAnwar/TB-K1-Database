<?php

namespace App\Services\Pdf;

use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PdfDocumentService
{
    public function downloadOrdersReport(iterable $orders, string $filename = 'laporan-pesanan.pdf'): Response
    {
        return $this->downloadGeneratedPdf(
            $this->buildOrdersReport($orders),
            $filename
        );
    }

    public function downloadOrderReceipt(Order $order, string $filename): Response
    {
        return $this->downloadGeneratedPdf(
            $this->buildOrderReceipt($order),
            $filename
        );
    }

    public function buildOrdersReport(iterable $orders): string
    {
        $this->loadLibrary();

        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $this->renderReportHeader($pdf, 'LAPORAN PESANAN LAPAK GAMING', 'Tanggal Export: ' . now()->translatedFormat('d F Y H:i'));

        $pdf->SetFillColor(15, 23, 42);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $headers = ['No', 'Invoice', 'Buyer', 'Seller', 'Status', 'Total', 'Tanggal'];
        $widths = [12, 42, 44, 44, 35, 40, 40];

        foreach ($headers as $index => $header) {
            $pdf->Cell($widths[$index], 8, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(30, 41, 59);

        foreach ($orders as $index => $order) {
            $buyer = $this->safeText($order->buyer?->name ?? 'Unknown');
            $seller = $this->safeText($order->seller?->name ?? 'Unknown');
            $status = $this->safeText(strtoupper($order->status_label ?? $order->status ?? '-'));
            $total = 'Rp ' . number_format($this->resolveGrandTotal($order), 0, ',', '.');
            $createdAt = optional($order->created_at)->translatedFormat('d M Y H:i') ?? '-';

            $pdf->Cell($widths[0], 8, (string) ($index + 1), 1, 0, 'C');
            $pdf->Cell($widths[1], 8, $this->limitText($this->safeText($order->invoice_number ?? '-'), 24), 1, 0, 'L');
            $pdf->Cell($widths[2], 8, $this->limitText($buyer, 26), 1, 0, 'L');
            $pdf->Cell($widths[3], 8, $this->limitText($seller, 26), 1, 0, 'L');
            $pdf->Cell($widths[4], 8, $this->limitText($status, 18), 1, 0, 'C');
            $pdf->Cell($widths[5], 8, $this->limitText($this->safeText($total), 22), 1, 0, 'R');
            $pdf->Cell($widths[6], 8, $this->limitText($this->safeText($createdAt), 22), 1, 0, 'C');
            $pdf->Ln();
        }

        if ($this->countIterable($orders) === 0) {
            $pdf->Cell(array_sum($widths), 12, 'Tidak ada data pesanan untuk ditampilkan.', 1, 1, 'C');
        }

        return $pdf->Output('S');
    }

    public function buildOrderReceipt(Order $order): string
    {
        $this->loadLibrary();
        $order->loadMissing(['buyer', 'seller', 'items.product', 'financial']);

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->SetMargins(14, 14, 14);
        $pdf->AddPage();

        $this->renderReportHeader($pdf, 'KWITANSI PESANAN', 'Dicetak pada: ' . now()->translatedFormat('d F Y H:i'));

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(0, 8, $this->safeText($order->invoice_number ?? $order->order_code ?? 'INV-UNKNOWN'), 0, 1, 'L');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(51, 65, 85);
        $pdf->Cell(0, 6, 'Status: ' . $this->safeText($order->status_label ?? $order->status ?? '-'), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Tanggal Pesanan: ' . $this->safeText(optional($order->created_at)->translatedFormat('d F Y H:i') ?? '-'), 0, 1, 'L');
        $pdf->Ln(2);

        $this->renderInfoBox($pdf, 'Buyer', [
            $this->safeText($order->buyer?->name ?? '-'),
            $this->safeText($order->buyer?->email ?? '-'),
        ]);
        $this->renderInfoBox($pdf, 'Seller', [
            $this->safeText($order->seller?->name ?? '-'),
            $this->safeText($order->seller?->email ?? '-'),
        ]);

        $pdf->Ln(2);
        $pdf->SetFillColor(15, 23, 42);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(92, 8, 'Item', 1, 0, 'C', true);
        $pdf->Cell(18, 8, 'Qty', 1, 0, 'C', true);
        $pdf->Cell(42, 8, 'Harga', 1, 0, 'C', true);
        $pdf->Cell(0, 8, 'Subtotal', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(30, 41, 59);

        foreach ($order->items as $item) {
            $itemName = $this->safeText($item->name_snapshot ?? $item->product?->name ?? 'Produk');
            $quantity = (string) $item->quantity;
            $price = 'Rp ' . number_format((float) ($item->price_snapshot ?? $item->price ?? 0), 0, ',', '.');
            $subtotal = 'Rp ' . number_format((float) ($item->subtotal ?? ((float) ($item->price_snapshot ?? $item->price ?? 0) * (int) $item->quantity)), 0, ',', '.');

            $this->renderReceiptRow($pdf, [
                ['width' => 92, 'text' => $itemName, 'align' => 'L'],
                ['width' => 18, 'text' => $quantity, 'align' => 'C'],
                ['width' => 42, 'text' => $price, 'align' => 'R'],
                ['width' => 0, 'text' => $subtotal, 'align' => 'R'],
            ], 5);
        }

        if ($order->items->isEmpty()) {
            $pdf->Cell(0, 10, 'Tidak ada item pesanan.', 1, 1, 'C');
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 7, 'Ringkasan Pembayaran', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 7, 'Metode Pembayaran', 1, 0, 'L');
        $pdf->Cell(0, 7, $this->safeText(ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Belum dipilih'))), 1, 1, 'L');
        $pdf->Cell(50, 7, 'Total Pembayaran', 1, 0, 'L');
        $pdf->Cell(0, 7, 'Rp ' . number_format($this->resolveGrandTotal($order), 0, ',', '.'), 1, 1, 'L');

        if ($order->notes) {
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, 'Catatan Pesanan', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, $this->safeText($order->notes), 1, 'L');
        }

        return $pdf->Output('S');
    }

    private function renderReportHeader(\FPDF $pdf, string $title, string $subtitle): void
    {
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(0, 12, $this->safeText($title), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, $this->safeText($subtitle), 0, 1, 'C');
        $pdf->Ln(4);
    }

    private function renderInfoBox(\FPDF $pdf, string $title, array $lines): void
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->Cell(0, 8, $title, 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($lines as $line) {
            $pdf->Cell(0, 7, $line, 1, 1, 'L');
        }
    }

    private function renderReceiptRow(\FPDF $pdf, array $columns, float $lineHeight = 5): void
    {
        $maxLines = 1;

        foreach ($columns as $column) {
            $width = (float) ($column['width'] ?? 0);
            $text = (string) ($column['text'] ?? '');

            if ($width > 0) {
                $maxLines = max($maxLines, $this->estimatePdfLines($pdf, $text, $width));
            }
        }

        $rowHeight = max($lineHeight, $maxLines * $lineHeight);
        $startX = $pdf->GetX();
        $startY = $pdf->GetY();
        $availableWidth = 182;

        foreach ($columns as $column) {
            $width = (float) ($column['width'] ?? 0);
            $text = $this->safeText((string) ($column['text'] ?? ''));
            $align = (string) ($column['align'] ?? 'L');
            $columnX = $pdf->GetX();

            if ($width <= 0) {
                $width = max(0, $availableWidth - ($columnX - $startX));
            }

            $pdf->Rect($columnX, $startY, $width, $rowHeight);
            $pdf->MultiCell($width, $lineHeight, $text, 0, $align);
            $pdf->SetXY($columnX + $width, $startY);
        }

        $pdf->SetXY($startX, $startY + $rowHeight);
    }

    private function safeText(?string $value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', (string) $value) ?? '');

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);

            if ($converted !== false) {
                $value = $converted;
            }
        }

        return preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $value) ?? '';
    }

    private function estimatePdfLines(\FPDF $pdf, string $text, float $width): int
    {
        $text = trim(preg_replace('/\s+/u', ' ', str_replace("\r", '', $text)) ?? '');

        if ($text === '') {
            return 1;
        }

        $maxWidth = max(1.0, $width - 2);
        $lines = 1;

        foreach (preg_split('/\n+/', $text) ?: [] as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                $lines++;
                continue;
            }

            $lineWidth = 0.0;
            foreach (preg_split('/\s+/', $paragraph) ?: [] as $word) {
                $wordWidth = $pdf->GetStringWidth($word . ' ');

                if ($lineWidth > 0 && ($lineWidth + $wordWidth) > $maxWidth) {
                    $lines++;
                    $lineWidth = $wordWidth;
                    continue;
                }

                if ($wordWidth > $maxWidth) {
                    $lines += (int) ceil($wordWidth / $maxWidth);
                    $lineWidth = 0.0;
                    continue;
                }

                $lineWidth += $wordWidth;
            }

            $lines++;
        }

        return max(1, $lines - 1);
    }

    private function resolveGrandTotal(Order $order): float
    {
        return (float) ($order->grand_total ?? $order->financial?->grand_total ?? $order->total_price ?? 0);
    }

    private function limitText(string $value, int $length): string
    {
        if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($value) > $length) {
            return mb_substr($value, 0, $length - 3) . '...';
        }

        if (strlen($value) > $length) {
            return substr($value, 0, $length - 1) . '...';
        }

        return $value;
    }

    private function countIterable(iterable $items): int
    {
        if ($items instanceof Collection) {
            return $items->count();
        }

        if (is_array($items)) {
            return count($items);
        }

        $count = 0;
        foreach ($items as $_item) {
            $count++;
        }

        return $count;
    }

    private function loadLibrary(): void
    {
        if (! class_exists('FPDF', false)) {
            require_once base_path('library_pdf/fpdf.php');
        }
    }

    private function downloadGeneratedPdf(string $pdfContent, string $filename)
    {
        if (ob_get_length()) {
            ob_end_clean();
        }

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Content-Length' => (string) strlen($pdfContent),
        ]);
    }
}
