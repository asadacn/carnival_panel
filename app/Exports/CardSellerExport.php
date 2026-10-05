<?php

namespace App\Exports;

use App\Models\CardSeller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CardSellerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'SL',
            'Retailer Name',
            'Contact Number',
            'Shop Name',
            'Shop Address',
        ];
    }

    public function collection()
    {
        return CardSeller::query()->orderBy('id')->get(['name', 'contact', 'store_title', 'address']);
    }

    public function map($seller): array
    {
        static $sl = 0;

        return [
            ++$sl,
            $seller->name,
            $seller->contact,
            $seller->store_title,
            $seller->address,
        ];
    }
}
