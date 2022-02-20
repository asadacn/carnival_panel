<?php

namespace App\Repositories;

use App\Models\SMS_TEMPALTE;
use App\Repositories\BaseRepository;

/**
 * Class SMS_TEMPALTERepository
 * @package App\Repositories
 * @version February 20, 2022, 1:57 pm UTC
*/

class SMS_TEMPALTERepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'sms_template'
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
        return SMS_TEMPALTE::class;
    }
}
