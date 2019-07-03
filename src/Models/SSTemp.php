<?php

namespace Tchoblond59\SSTemp\Models;

use App\Sensor;

use App\Message;
use Carbon\Carbon;
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

    public function getLastTemperature()
    {
        $last_message = Message::where('node_address', '=', $this->node_address)
            ->where('sensor_address', '=', $this->sensor_address)
            ->where('command', '=', 1)
            ->orderBy('created_at', 'desc')->first();
        if($last_message != null)
        {
            return $last_message->value;
        }
        else
            return null;
    }

    public function getTempStats()
    {
        $one_week_ago = Carbon::now()->subWeek();
        $data = Message::select(DB::raw('avg(value) as temp_avg, DATE(created_at) as day, HOUR(created_at) as hour'))
            ->where('node_address', '=', $this->node_address)
            ->where('sensor_address', '=', $this->sensor_address)
            ->where('command', '=', 1)
            ->whereDate('created_at', '>=', $one_week_ago)
            ->groupBy('day', 'hour')
            ->get();
        return $data;
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