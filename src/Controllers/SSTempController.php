<?php

namespace Tchoblond59\SSTemp\Controllers;

use App\Command;
use App\Http\Controllers\Controller;
use Tchoblond59\SSTemp\Models\SstempConfigs;

class SSTempController extends Controller
{
    public function index($id)
    {
        $conf =SstempConfigs::where('sensor_id',$id);
        if($conf->exists()==false)
        {
           SstempConfigs::create(['sensor_id'=> $id]);
        }

        return view('sstemp::
        ')->with([


        ]);
    }

}
