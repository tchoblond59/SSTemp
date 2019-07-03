<?php

namespace Tchoblond59\SSTemp\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SSTempConfig extends Model
{
    public $table = 'sstemp_configs';

    public $timestamps = false;

    protected $dates = ['last_send'];

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
        $now = Carbon::now();
        $last_send = $this->last_send;
        if($last_send == null)//Never been sent so we force it
        {
            $last_send = Carbon::now();
            $duration = 99;
        }
        else
            $duration = $now->diffInMinutes($last_send);

        if($this->limit != null && $this->ssTemp->getLastTemperature() >= $this->limit && $duration >=30)
        {
            foreach ($this->emails as $email)
            {
                $email->send();
            }
            $this->last_send = Carbon::now();
            $this->save();
        }
    }
}