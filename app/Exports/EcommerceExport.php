<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EcommerceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    protected $headers;
    protected $type;

    public function __construct($data, $headers, $type = 'general')
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->type = $type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        switch ($this->type) {
            case 'daily_sales':
                return [
                    \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                    'R$ ' . number_format($item->total, 2, ',', '.'),
                    $item->count
                ];

            case 'top_products':
                return [
                    $item->title,
                    $item->shop_name ?: ($item->username ?: 'Sem nome'),
                    $item->total_sold
                ];

            case 'sales_by_store':
                return [
                    $item->shop_name ?: ($item->username ?: 'Sem nome'),
                    'R$ ' . number_format($item->total_sales, 2, ',', '.'),
                    $item->total_orders
                ];

            case 'order_status':
                return [
                    ucfirst($item->order_status),
                    $item->count
                ];

            case 'general':
            default:
                return [
                    $item->order_number ?: 'N/A',
                    $item->user->shop_name ?: ($item->user->username ?: 'Sem nome'),
                    ($item->billing_fname . ' ' . $item->billing_lname) ?: 'N/A',
                    'R$ ' . number_format($item->total, 2, ',', '.'),
                    ucfirst($item->order_status),
                    $item->payment_status ?: 'N/A',
                    $item->created_at->format('d/m/Y H:i')
                ];
        }
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para o cabeçalho
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }

    public function title(): string
    {
        $titles = [
            'daily_sales' => 'Vendas Diárias',
            'top_products' => 'Produtos Mais Vendidos',
            'sales_by_store' => 'Vendas por Loja',
            'order_status' => 'Status dos Pedidos',
            'general' => 'Relatório Geral'
        ];

        return $titles[$this->type] ?? 'Relatório';
    }
}
