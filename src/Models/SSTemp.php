<?php

namespace Tchoblond59\SSTemp\Models;

use App\Sensor;

use App\Message;
use DB;

class SSTemp extends Sensor
{
    public function getWidget(\App\Widget $widget)
    {
        $sensor = Sensor::findOrFail($widget->sensor_id);
        $last_message = Message::where('node_address', '=', $sensor->node_address)
            ->where('sensor_address', '=', $sensor->sensor_address)
            ->where('command', '=', 1)
            ->orderBy('created_at', 'desc')->first();
        if($last_message==null)
        {
            return view('sstemp::widget_empty')->with(['widget' => $widget,
                'last_message' => $last_message]);
        }
        return view('sstemp::widget')->with(['widget' => $widget,
            'last_message' => $last_message]);
    }

    public function getJs()
    {
        return ['js/tchoblond59/sstemp/sstemp.js'];
    }

    public function config()
    {
        return $this->hasOne(SSTempConfig::class, 'sensor_id');
    }
}