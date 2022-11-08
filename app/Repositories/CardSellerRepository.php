<?php

namespace App\Repositories;

use App\Models\CardSeller;
use App\Repositories\BaseRepository;

/**
 * Class CardSellerRepository
 * @package App\Repositories
 * @version September 27, 2022, 8:35 pm UTC
*/

class CardSellerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'seller',
        'contact',
        'store_title',
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
