<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembershipExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public $memberships;

    public function __construct($memberships)
    {
        $this->memberships = $memberships;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->memberships;
    }

    public function map($membership): array
    {
        return [
            $membership->id,
            $membership->user->username ?? '',
            $membership->user->shop_name ?? '',
            $membership->package->title ?? '',
            'R$ ' . number_format($membership->price, 2, ',', '.'),
            $membership->status == 1 ? 'Ativo' : 'Inativo',
            $membership->start_date,
            $membership->expire_date,
            $membership->payment_method ?? '-',
            $membership->created_at->format('d/m/Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Usuário',
            'Nome da Loja',
            'Plano',
            'Preço',
            'Status',
            'Data Início',
            'Data Expiração',
            'Método Pagamento',
            'Data Criação'
        ];
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
}
