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
        foreach ($allusers as $user){

           $bwlimit=$user->userGroup->radgroupcheck()->where('attribute','LIKE','%Bandwidth')->first();
           $sessionlimit=$user->userGroup->radgroupcheck()->where('attribute','LIKE','%Session')->first();
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
           $on=RadAcct::where('username','=',$user->username)->whereNull('acctstoptime')->whereBetween('acctupdatetime',[Carbon::now()->format('Y-m-d H:i:s'),Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s')])->get();
           if (!empty($on)){
               $accts[$user->username]['status']='off';

           }else{
               $accts[$user->username]['status']='on';
               $accts[$user->username]['shared']=$user->userGroup->radgroupcheck->where('attribute','=','Simultaneous-Use')->first()->value;
           }
           $sim=$user->userGroup->radgroupcheck()->where('attribute','=','Simultaneous-Use')->first();
           if ($sim){
               $accts[$user->username]['shared']=$user->userGroup->radgroupcheck->where('attribute','=','Simultaneous-Use')->first()->value;
           }else{
               $accts[$user->username]['shared']=null;
           }

        }
//        dd($accts);
        return view('user.index',['users'=>$allusers,'accts'=>$accts]);
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
            'username'=>'required',
            'password'=>'required',
            'profile'=>'required'
        ];
        $messages=[
            'username.required'=>'Usename wajib diisi',
            'password.required'=>'Password wajib diisi',
            'profile.required'=>'Silahkan memilih profile'
        ];
        $validator=Validator::make($request->all(),$rules,$messages);
        if (!$validator->fails()){
            RadCheck::updateOrCreate(['username'=>$request->username],['attribute'=>'Cleartext-Password','op'=>':=','value'=>$request->password]);
            RadUserGroup::updateOrCreate(['username'=>$request->username,'groupname'=>$request->profile]);
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
        $group = RadUserGroup::where('username','=',$user->username)->where('groupname','=',$request->profile)->first();
        if (empty($group)){
            RadUserGroup::create(['username'=>$user->username,'groupname'=>$request->profile]);
        }else{
            RadUserGroup::where('username','=',$user->username)->update(['groupname'=>$request->profile]);
        }
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
