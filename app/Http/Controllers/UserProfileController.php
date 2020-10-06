<?php

namespace App\Http\Controllers;

use App\RadGroupCheck;
use App\RadGroupReply;
use App\RadUserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userprofile=RadUserGroup::all()->unique('groupname');
        return view('userprofile.index',['userprofile'=>$userprofile]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attribute($group){
        $attrs=[];
        $attrs['check']=[];
        $attrs['reply']=[];
        $usergroup=RadUserGroup::where('groupname','=',$group)->first();
        foreach($usergroup->radgroupcheck as $key=>$value){
            array_push($attrs['check'],$value);
        }
        foreach($usergroup->radgroupreply as $key=>$value){
            array_push($attrs['reply'],$value);
        }
        return view('userprofile.attribute.list',['attrs'=>$attrs,'groupname'=>$group]);
    }

    public function deleteAttribute(Request $request){
        if ($request->type=='check'){
            RadGroupCheck::find($request->id)->delete();
        }
        if ($request->type=='reply'){
            RadGroupReply::find($request->id)->delete();
        }
        return response()->json(true);
    }

    public function create()
    {
        //
        return view('userprofile.create');
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
            'profilename'=>'required'
        ];
        $message=[
            'profilename.required'=>'Profile Name Harus diisi'
        ];
        $validator=Validator::make($request->all(),$rules,$message);
        if (!$validator->fails()){
            RadUserGroup::updateOrCreate(['groupname'=>$request->profilename]);
            RadGroupCheck::create(['groupname'=>$request->profilename,'attribute'=>'Simultaneous-Use','op'=>':=','value'=>1]);
            return redirect()->back()->with('success','User Profile berhasil disimpan');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $radusergroup=RadUserGroup::where('groupname','=',$id)->first();

        $radusers=$radusergroup->user;
        foreach ($radusers as $user){
           $user->delete();
        }
        if(!empty($radusergroup->radgroupcheck)){
            $radusergroup->radgroupcheck()->delete();
        }
        if (!empty($radusergroup->radgroupreply)){
            $radusergroup->radgroupreply()->delete();
        }
        RadUserGroup::where('groupname','=',$id)->delete();

        return response()->json(true);

    }

    public function addAttribute($group){
        $userprofile=RadUserGroup::where('groupname','=',$group)->first();
        return view('userprofile.attribute.add',['userprofile'=>$userprofile]);
    }

    public function storeAttribute(Request $request){
        $rules=[];
        $message=[];
        if ($request->has('quotacheck')){
            $rules["quota"]="required";
            $rules['quotavalue']='required';
            $message['quota.required']="Pilih limit";
            $message['quotavalue.required']='Value tidak boleh kosong';
        }
        if ($request->has('timecheck')){
            $rules["time"]="required";
            $rules['timevalue']='required';
            $message['time.required']="Pilih limit";
            $message['timevalue.required']='Value tidak boleh kosong';
        }
        if ($request->has('sharedcheck')){
            $rules['sharedvalue']='required';
            $message['sharedvalue.required']="Value tidak boleh kosong";
        }

        $validator= Validator::make($request->all(),$rules,$message);
        if (!$validator->fails()){
            if ($request->has('quotacheck')){
                RadGroupCheck::where('attribute','LIKE','%Bandwidth')->delete();
                RadGroupCheck::updateOrCreate(['groupname'=>$request->groupname,'attribute'=>$request->quota],['op'=>':=','value'=>$request->quotavalue*1024*1024]);
            }
            if ($request->has('timecheck')){
                RadGroupCheck::where('attribute','LIKE','%Session')->delete();
                RadGroupCheck::updateOrCreate(['groupname'=>$request->groupname,'attribute'=>$request->time],['op'=>':=','value'=>$request->timevalue*3600]);
               // RadGroupReply::updateOrCreate(['groupname'=>$request->groupname,'attribute'=>'Session-Timeout'],['op'=>':=','value'=>$request->timevalue*3600]);
            }
            if ($request->has('sharedcheck')){
                RadGroupCheck::updateOrCreate(['groupname'=>$request->groupname,'attribute'=>'Simultaneous-Use'],['op'=>':=','value'=>$request->sharedvalue]);
            }
//            else{
//                RadGroupCheck::updateOrCreate(['groupname'=>$request->groupname,'attribute'=>'Simultaneous-Use'],['op'=>':=','value'=>1]);
//            }
            return redirect()->back()->with('success','Attribute telah ditambahkan');
        }else{
            return redirect()->back()->withErrors($validator->errors());
        }
    }
}
