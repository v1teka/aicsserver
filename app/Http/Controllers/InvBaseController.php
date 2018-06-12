<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\inventory;
use app\inventory_type;
use app\classroom;
use app\computer;

class InvBaseController extends Controller
{
    public function ShowFull(){
        $list = invObject::all();
        return view('inventory_list_page')->with('message', $list);
    }

    public function Update(Request $request){
        /*$this->validate($request,
        [
            //'id'=>'required',
            'invNum'=>'required',
            'description'=>'required',
            'room'=>'required',
        ]);*/
        $data = $request->all();
        $computer = new invObject;
        $computer->fill($data);
        $computer->save();
        return 'polucheno servakom';
    }

    public function Register(Request $request){
        $data = $request->all();
        if(/* проверка */true)
            return 'the machine is already registered';
        else{
            return 'machine has been registered';
        }
    }

    public function getMap(Request $request) {
        $computers = DB::table('inventories')
            ->join('computers', 'inventories.id', '=', 'computers.inv_id')
            ->join('inventory_types', 'inventories.type_id', '=', 'inventory_types.id')
            ->join('classrooms', 'inventories.classroom_id', '=', 'classrooms.id')
            ->select('inventories.id','inventories.number' , 'inventories.type_id', 'inventories.x'
                , 'inventories.y' , 'computers.mac', 'computers.state')
            ->where('classrooms.title', $request['room'])
            ->get();
        $invent = DB::table('inventories')
            ->join('inventory_types', 'inventories.type_id', '=', 'inventory_types.id')
            ->join('classrooms', 'inventories.classroom_id', '=', 'classrooms.id')
            ->select('inventories.id','inventories.number' , 'inventories.type_id', 'inventories.x'
                , 'inventories.y')
            ->where('classrooms.title', $request['room'])
            ->where('inventory_types.title', '<>', 'Компьютер')
            ->get();
        return $computers->merge($invent)->toJson();
    }

    public function Info(Request $request){
        $ip = DB::table('computers')
            ->select('ip')
            ->where('mac', $request['address'])
            ->orWhere('ip', $request['address'])
            ->pluck('ip')[0];
        if($ip){
            $fp = fsockopen($ip, 9999);
            if($request['t']==4) fputs($fp, "4".$request['message']);
            else fputs($fp, $request['t']);
            $answer = stream_get_contents($fp);
            fclose($fp);
            return $answer;
        }else return 0;
        
    }

    public function Draw(Request $request){
        if($request['room']){
            return view('room')->with('number', $request['room']);
        }else{
            return view('rooms');
        }
    }

    private function ipMac($scan){
        preg_match("([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})", $scan, $mac);
        preg_match("(\d+\.\d+\.\d+\.\d+)", $scan, $ip);
        DB::table('computers')
            ->where('mac', $mac[0])
            ->update(['ip' => $ip[0]]);
        return 1;
    }

    public function arpScan(Request $request){
        $mac_scan = shell_exec('getmac');
        $mac_scan = explode("\n", $mac_scan);
        foreach($mac_scan as $scan) {
            if(substr_count($scan, $request['address'])>0){
                preg_match("((?:[0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2})", $scan, $mac);
                DB::table('computers')
                    ->where('mac', $mac[0])
                    ->update(['ip' => '127.0.0.1']);
                return 1;
            } 
        }

        $arp_scan = shell_exec('arp -a');
        $arp_scan = explode("\n", $arp_scan);
        foreach($arp_scan as $scan) {
            if(substr_count($scan, $request['address'])>0){
                preg_match("((?:[0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2})", $scan, $mac);
                preg_match("(\d+\.\d+\.\d+\.\d+)", $scan, $ip);
                DB::table('computers')
                    ->where('mac', $mac[0])
                    ->update(['ip' => $ip[0]]);
                return 1;
            }
        }
        return 0;
    }
}
