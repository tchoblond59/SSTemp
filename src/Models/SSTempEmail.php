<?php

namespace Tchoblond59\SSTemp\Models;

use Illuminate\Database\Eloquent\Model;

class SSTempEmail extends Model
{
    public $table = 'sstemp_emails';
    public function config()
    {
        return $this->belongsTo(SSTempConfig::class, 'sstemp_config_id');
    }
}