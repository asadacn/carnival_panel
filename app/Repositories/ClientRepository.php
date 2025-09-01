<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\BaseRepository;

/**
 * Class ClientRepository
 * @package App\Repositories
*/

class ClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'contact',
        'secondary_contact',
        'address',
        'package',
        'username',
        'expiration',
        'status',
        'Onu_mac',
        'onu_serial',
        'onu_brand',
        'onu_free',
        'onu_returned',
        'onu_owner',
        'cable',
        'cable_returned',
        'cable_owner',
        'billing_type',
        'gps_location',
        'comment',
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
        return Client::class;
    }
}
