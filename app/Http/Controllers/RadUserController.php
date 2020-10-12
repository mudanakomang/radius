<?php

namespace App\Http\Controllers;

use App\RadAcct;
use App\RadCheck;
use App\RadGroupCheck;
use App\RadUserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RadUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bwUsage($profile,$username){
        $bw=explode('-',$profile);
        switch ($bw[0]){
            case 'Daily':
                $q=[Carbon::now('Asia/Jakarta')->copy()->startOfDay()->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->copy()->endOfDay()->format('Y-m-d H:i:s')];
                $sql='select sum(acctinputoctets)+sum(acctoutputoctets) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between  \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Daily';
                break;
            case 'Weekly':
                $q=[Carbon::now('Asia/Jakarta')->startOfWeek(CARBON::SUNDAY)->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->endOfWeek(CARBON::MONDAY)->format('Y-m-d H:i:s')];
                $sql='select sum(acctinputoctets)+sum(acctoutputoctets) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between  \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Weekly';
                break;
            case 'Monthly':
                $q=[Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d H:i:s')];
                $sql='select sum(acctinputoctets)+sum(acctoutputoctets) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Monthly';
                break;
            default:
                $sql='select sum(acctinputoctets)+sum(acctoutputoctets) as total from radacct WHERE username=\''.$username.'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Max';
                break;

        }
        return [$usage[0]->total,$type];
    }
    public function sessionUsage($profile,$username){
        $bw=explode('-',$profile);
        switch ($bw[1]){
            case 'Daily':
                $q=[Carbon::now('Asia/Jakarta')->copy()->startOfDay()->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->copy()->endOfDay()->format('Y-m-d H:i:s')];
                $sql='select sum(acctsessiontime) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between  \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Daily';
                break;
            case 'Weekly':
                $q=[Carbon::now('Asia/Jakarta')->startOfWeek(CARBON::SUNDAY)->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->endOfWeek(CARBON::MONDAY)->format('Y-m-d H:i:s')];
                $sql='select sum(acctsessiontime) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between  \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Weekly';
                break;
            case 'Monthly':
                $q=[Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d H:i:s'),Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d H:i:s')];
                $sql='select sum(acctsessiontime) as total from radacct WHERE username=\''.$username.'\' and acctupdatetime between \''.$q[0].'\' and \''.$q[1].'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Monthly';
                break;
            default:
                $sql='select sum(acctsessiontime) as total from radacct WHERE username=\''.$username.'\'';
                $usage=DB::select(DB::raw($sql));
                $type='Max';
                break;

        }
        return [$usage[0]->total,$type];
    }
    public function index()
    {
        //
        $users=[];

        $allusers=RadCheck::all();
        $useracctall=DB::table('radacct')->select('username')->get();
        $collection = collect($useracctall);
        $useracct = $collection->unique()->values()->all();

        foreach ($allusers as $user){
            if (!empty($user->userGroup)) {
                $bwlimit = $user->userGroup->radgroupcheck()->where('attribute', 'LIKE', '%Bandwidth')->first();
                $sessionlimit=$user->userGroup->radgroupcheck()->where('attribute','LIKE','%Session')->first();
            }else{
                $bwlimit=null;
                $sessionlimit=null;
            }


           if (!empty($bwlimit)){
               $accts[$user->username]['bwlimit']=$bwlimit->value;
               $accts[$user->username]['bwusage']=$this->bwUsage($bwlimit->attribute,$user->username)[0];
               $accts[$user->username]['bwtype']=$this->bwUsage($bwlimit->attribute,$user->username)[1];
           }else{
               $accts[$user->username]['bwlimit']=null;
               $accts[$user->username]['bwusage']=null;
               $accts[$user->username]['bwtype']=null;
           }
            if (!empty($sessionlimit)){
                $accts[$user->username]['sessionusage']=$this->sessionUsage($sessionlimit->attribute,$user->username)[0];
                $accts[$user->username]['sessionlimit']=$sessionlimit->value;
                $accts[$user->username]['sestype']=$this->sessionUsage($sessionlimit->attribute,$user->username)[1];
            }else{
                $accts[$user->username]['sessionusage']=null;
                $accts[$user->username]['sessionlimit']=null;
                $accts[$user->username]['sestype']=null;
            }
           $on=RadAcct::where('username','=',$user->username)->whereNull('acctstoptime')->whereBetween('acctupdatetime',[Carbon::now()->subMinutes(10)->format('Y-m-d H:i:s'),Carbon::now()->format('Y-m-d H:i:s')])->get();
           if ($on->isEmpty()){
               $accts[$user->username]['status']='off';
           }else{
               $accts[$user->username]['status']='on';
               //$accts[$user->username]['shared']=$user->userGroup->radgroupcheck->where('attribute','=','Simultaneous-Use')->first()->value;
           }
           if (!empty($user->userGroup)){
               $sim=$user->userGroup->radgroupcheck()->where('attribute','=','Simultaneous-Use')->first();
           }else{
               $sim=null;
           }

           if (!empty($sim)){
               $accts[$user->username]['shared']=$user->userGroup->radgroupcheck->where('attribute','=','Simultaneous-Use')->first()->value;
           }else{
               $accts[$user->username]['shared']=null;
           }

        }
 //       dd($allusers);
//
        if (isset($accts)){
            return view('user.index',['users'=>$allusers,'accts'=>$accts,'useracct'=>$useracct]);
        }else{
            return view('user.index',['users'=>$allusers,'useracct'=>$useracct,'accts'=>[]]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $rules=[
            'amount'=>'required',
            'speed'=>'required',

        ];
        $messages=[
            'amount.required'=>'Amount not be empty',
            'speed.required'=>'Speed not be empty',

        ];
        $validator=Validator::make($request->all(),$rules,$messages);
        if (!$validator->fails()){
            $amount = $request->amount;
            function generateRandomString() {
                $randomString = $_REQUEST['speed']; // A
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);

                for ($i = 0; $i < 5; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return strtoupper($randomString);
            }

            $result=[];
            for($i=0;$i<=$amount-1;$i++){
                array_push($result,generateRandomString());
            }

            foreach ($result as $hasilrandom)
            {
                RadCheck::updateOrCreate(['username'=>$hasilrandom],['attribute'=>'Cleartext-Password','op'=>':=','value'=>$hasilrandom]);
            }



//            RadCheck::updateOrCreate(['username'=>$result],['attribute'=>'Cleartext-Password','op'=>':=','value'=>$result]);
//            RadUserGroup::updateOrCreate(['username'=>$request->username,'groupname'=>$request->profile]);
//            DB::statement('CALL delete_empty_usergroup()');
            return redirect()->back()->with('success','User berhasil disimpan');
        }else{
            return redirect()->back()->withErrors($validator->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user=RadCheck::find($id);
        $group=$user->userGroup->groupname;
        return view('user.edit',['user'=>$user,'group'=>$group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

    }
    public function updateuser(Request $request,$id){
        $user=RadCheck::find($id);
        $user->username=$request->username;
        $user->value=$request->password;
        $user->save();
        $groupexists=RadUserGroup::where('username','=',$user->username)->get();
        if (!empty($groupexists)){
            RadUserGroup::where('username','=',$user->username)->delete();
        }
        //RadUserGroup::where('username','=',$user->username)->where('groupname','=',$request->profile)->delete();
        RadUserGroup::create(['username'=>$user->username,'groupname'=>$request->profile]);

        return redirect()->back()->with('success','Username berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteUser(Request $request){
        $user=RadCheck::find($request->id);
        $group=RadUserGroup::where('username','=',$user->username);
        $group->delete();
        $user->delete();
        return response()->json(true);
    }
    public function destroy($id)
    {
        //
        dd($id);
    }
}
