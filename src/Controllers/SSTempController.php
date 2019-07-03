<?php

namespace Tchoblond59\SSTemp\Controllers;

use App\Command;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tchoblond59\SSTemp\Models\SSTemp;
use Tchoblond59\SSTemp\Models\SSTempConfig;
use Tchoblond59\SSTemp\Models\SSTempEmail;

class SSTempController extends Controller
{
    public function index($id)
    {
        $conf = SSTempConfig::where('sensor_id', $id)->first();
        if(!$conf->exists())
        {
            $conf = new SSTempConfig();
            $conf->sensor_id = $id;
            $conf->save();
        }
        $confmail = SSTempEmail::where('sstemp_config_id', $conf->id)->get();
        $sstemp = SSTemp::find($id);
        $temp_stats = $sstemp->getTempStats();
        $data_chart[] = ['Date', 'Température'];
        foreach ($temp_stats as $temp)
        {
            $data_chart[] = [$temp->day.' '.$temp->hour.'h', $temp->temp_avg];
        }
        return view('sstemp::gestion')->with([
            'last_temp' => $conf->limit,
            'id' => $id,
            'mails' => $confmail,
            'data_chart' => json_encode($data_chart)
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'temp_lim' => 'numeric',
            'mail' => 'email',
        ]);
        $limit = $request->temp_lim;
        $mail = $request->mail;
        if($limit !== null)
        {
            $conf = SSTempConfig::where('sensor_id', $id)->get()->first();
            $conf->limit = $limit;
            $conf->save();
        }
        if($mail !== null)
        {
            $conf = SSTempConfig::where('sensor_id', $id)->get()->first();
            $confmail = new SSTempEmail();
            $confmail->sstemp_config_id = $conf->id;
            $confmail->email = $mail;
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
