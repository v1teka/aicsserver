<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests;
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
        $map = DB::table('inventories')
            ->join('computers', 'inventories.id', '=', 'computers.inv_id')
            ->join('inventory_types', 'inventories.type_id', '=', 'inventory_types.id')
            ->select('inventories.id'. 'computers.mac', 'inventories.type_id', 'computers.state'
                , 'inventories.x', 'inventories.y','inventories.number')
            ->where('classrooms.title', $request['room'])
            ->get();
        return $map->toJson();
    }

    public function Info(Request $request){
        $fp = fsockopen($request['ip'], 9999);
        if($request['t']==4) fputs($fp, "4".$request['message']);
        else fputs($fp, $request['t']);
        $answer = stream_get_contents($fp);
        fclose($fp);
        return $answer;
    }

    public function Map(Request $request){
    
        return 1;
    }

    public function Draw(Request $request){
        if($request['room']){
            return view('room')->with('number', $request['room']);
        }else{
            return view('rooms');
        }
    }

    public function isOnline(Request $request){
        
    }

    public function arpScan(Request $request){
        $arp_scan = shell_exec('arp -a');
        $arp_scan = explode("\n", $arp_scan);
        $result="";
        foreach($arp_scan as $scan) {
            if($request['ip']=='127.0.0.1' || strpos($scan, $request['ip']))
                return 1;
        }
        return 0;
    }
}
