<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DatahubPriceList extends BaseModel
{
    use SoftDeletes;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = ['ChargeType', 'ChargeTypeCode', 'GLN_number', 'ValidFrom', 'Note'];

    public function getMatchingInDb(): DatahubPriceList|bool
    {
        $db_record = $this->where([
            'GLN_Number'=>$this->getAttribute('GLN_Number'),
            'ChargeType'=>$this->getAttribute('ChargeType'),
            'ChargeTypeCode'=>$this->getAttribute('ChargeTypeCode'),
            'Note'=>$this->getAttribute('Note'),
            'ValidFrom'=>$this->getAttribute('ValidFrom'),
            'ValidTo'=>$this->getAttribute('ValidTo'),
        ])->first();

        if($db_record) {
            return $this->getAttribute('GLN_Number') == $db_record->GLN_Number
                && $this->getAttribute('ChargeType') == $db_record->ChargeType
                && $this->getAttribute('ChargeTypeCode') == $db_record->ChargeTypeCode
                && $this->getAttribute('Note') == $db_record->Note
                && $this->getAttribute('ValidFrom') == $db_record->ValidFrom
                && $this->getAttribute('ValidTo') == $db_record->ValidTo;
        }
        return false;
    }
}
