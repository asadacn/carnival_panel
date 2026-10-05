<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CardSellerTemplate implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'SL',
            'Retailer Name',
            'Contact Number',
            'Shop Name',
            'Shop Address',
            'Village',
            'Union',
            'Upzila/Area',
            'District',
            'Division',
        ];
    }
}
