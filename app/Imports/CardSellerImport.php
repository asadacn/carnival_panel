<?php

namespace App\Imports;

use App\Models\CardSeller;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
class CardSellerImport implements ToModel, WithHeadingRow, WithProgressBar
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CardSeller([
            "name"=>$row['retailer name'],
            "contact"=>$row['contact number'],
            "shop name"=>$row['shop name'],
            "address"=>$row['shop address'],
            "village"=>$row['village'],
            "union"=>$row['union'],
        ]);
    }


}
