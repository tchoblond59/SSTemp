<?php

namespace Tchoblond59\SSTemp\Models;

use Illuminate\Database\Eloquent\Model;

class SSTempConfig extends Model
{
    public $table = 'sstemp_configs';

    public $timestamps = false;

    public function ssTemp()
    {
        return $this->belongsTo(SSTemp::class, 'sensor_id');
    }

    public function emails()
    {
        return $this->hasMany(SSTempEmail::class, 'sstemp_config_id');
    }

    public function sendAlert()
    {
        if($this->limit != null && $this->ssTemp->getLastTemperature() >= $this->limit)
        {
            foreach ($this->emails as $email)
            {
                $email->send();
            }
        }
    }
}