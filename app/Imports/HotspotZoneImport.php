<?php

namespace App\Imports;

use App\Models\HotspotZone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotspotZoneImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HotspotZone([
            "zone_id"=>$row['zone_id'],
            "zone_title"=>$row['zone_title'],
            "device_brand"=>$row['device_brand'],
            "device_mac"=>$row['device_mac'],
            "device_serial"=>$row['device_serial'],
            "onu_mac"=>$row['onu_mac'],
            "onu_brand"=>$row['onu_brand'],
            "card_seller"=>$row['card_seller'],
            "status"=>$row['status'],
            "has_ups"=>$row['has_ups'],
            "usp_model"=>$row['usp_model'],
            "usp_adapter"=>$row['usp_adapter'],
        ]);
    }
}
