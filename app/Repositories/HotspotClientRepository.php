<?php

namespace App\Repositories;

use App\Models\HotspotClient;
use App\Repositories\BaseRepository;

/**
 * Class HotspotClientRepository
 * @package App\Repositories
 * @version September 17, 2023, 11:30 pm +06
*/

class HotspotClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'contact',
        'cable',
        'cable_owner',
        'onu_mac',
        'onu_owner',
        'adrress'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HotspotClient::class;
    }
}
