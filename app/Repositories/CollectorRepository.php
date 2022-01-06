<?php

namespace App\Repositories;

use App\Models\Collector;
use App\Repositories\BaseRepository;

/**
 * Class CollectorRepository
 * @package App\Repositories
 * @version January 6, 2022, 4:35 pm UTC
*/

class CollectorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'fathers_name',
        'contact',
        'address',
        'nid'
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
        return Collector::class;
    }
}
