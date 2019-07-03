<?php

namespace Tchoblond59\SSTemp\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SSTempEmail extends Model
{
    public $table = 'sstemp_emails';
    public $timestamps = false;

    public function config()
    {
        return $this->belongsTo(SSTempConfig::class, 'sstemp_config_id');
    }

    public function send()
    {
        $temperature = $this->config->ssTemp->getLastTemperature();
        $time = Carbon::now()->format('d/m/Y à H:i');
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('sstemp::emails.alert_temp', ['temperature' => $temperature, 'time' => $time], function ($message) {
            $message
                ->to($this->email)
                ->subject('Alerte sonde température!');
        });
    }
}