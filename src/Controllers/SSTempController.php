<?php

namespace Tchoblond59\SSTemp\Controllers;

use App\Command;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tchoblond59\SSTemp\Models\SSTempConfig;
use Tchoblond59\SSTemp\Models\SSTempEmail;

class SSTempController extends Controller
{
    public function index($id)
    {
        $conf =SSTempConfig::where('sensor_id',$id)->get()->first();
        $confmail=SSTempEmail::where('sstemp_config_id',$conf->id)->get();

        if($conf->exists()==false)
        {
            $conf =new SSTempConfig();
            $conf->sensor_id = $id;
            $conf->save();
        }

        return view('sstemp::gestion')->with([
            'last_temp' => $conf->limit,
            'id'=>$id,
            'mails'=>$confmail

        ]);
    }

    public function update($id,Request $request)
    {


        $limit=$request->temp_lim;
        $mail=$request->mail;

        if ($limit !== null)
        {
            $conf=SSTempConfig::where('sensor_id',$id)->get()->first();

            $conf->limit=$limit;
            $conf->save();
        }

        if ($mail !== null)
        {
            $conf=SSTempConfig::where('sensor_id',$id)->get()->first();
            $confmail= new SSTempEmail();

            $confmail->sstemp_config_id = $conf->id;
            $confmail->email=$mail;
            $confmail->save();



        }

        return redirect()->back();
    }
    public function delete($id)
    {
        $mail = SSTempEmail::find($id);
        $mail->delete();
        return redirect()->back();
    }
}
