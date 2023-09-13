<?php

namespace App\Imports;

use App\Models\HotspotZone;
use Maatwebsite\Excel\Concerns\ToModel;

class HotspotZoneImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HotspotZone([
            //
        ]);
    }
}
