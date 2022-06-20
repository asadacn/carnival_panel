<?php

namespace App\Repositories;

use App\Models\CardSeller;
use App\Repositories\BaseRepository;

/**
 * Class CardSellerRepository
 * @package App\Repositories
 * @version June 6, 2022, 7:21 pm UTC
*/

class CardSellerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'contact',
        'shopName',
        'village',
        'union',
        'address'
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
        return CardSeller::class;
    }
}
