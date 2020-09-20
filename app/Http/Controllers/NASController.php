<?php

namespace App\Http\Controllers;

use App\Nas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class NASController extends Controller
{
    //NAS Table /Index
    public function index(){
        $nas=Nas::all();
        return view('nas.index',['nas'=>$nas]);
    }

//    NAS Edit
    public function edit($id){
        $nas=Nas::find($id);
        return view('nas.edit',['nas'=>$nas]);
    }

    public function update(Request $request,$id){
        $nas=Nas::find($id);
        $rules=[
          'nasname'=>'required',
          'shortname'=>'required',
          'type'=>'required',
          'secret'=>'required',
        ];
        $message=[
            'nasname.required'=>'NAS Name / IP harus diisi',
            'shortname.required'=>'Shortname harus diisi',
            'type.required'=>'Tipe harus diisi, jika tidak yakin isi saja "other"',
            'secret.required'=>'Secret harus diisi'
        ];
        $validator=Validator::make($request->all(),$rules,$message);
        if(!$validator->fails()){
            $nas->nasname=$request->nasname;
            $nas->shortname=$request->shortname;
            $nas->type=$request->type;
            $nas->secret=$request->secret;
            $nas->description=$request->description;
            $nas->save();

            return redirect()->back()->with('success','Perubahan berhasil disimpan');
        }else{
            return redirect()->back()->withErrors($validator->errors());
        }
    }

//    delete nas
    public function deleteNas(Request $request){
        $nas=Nas::find($request->id);
        $nas->delete();
        return response()->json(true);
    }

//    Create NAS

    public function create(){
        return view('nas.create');
    }
    public function store(Request $request){
        $rules=[
            'nasname'=>'required',
            'shortname'=>'required',
            'type'=>'required',
            'secret'=>'required',
        ];
        $message=[
            'nasname.required'=>'NAS Name / IP harus diisi',
            'shortname.required'=>'Shortname harus diisi',
            'type.required'=>'Tipe harus diisi, jika tidak yakin isi saja "other"',
            'secret.required'=>'Secret harus diisi'
        ];
        $validator=Validator::make($request->all(),$rules,$message);
        if (!$validator->fails()){
            $nas= new Nas();
            $nas->nasname=$request->nasname;
            $nas->shortname=$request->shortname;
            $nas->type=$request->type;
            $nas->secret=$request->secret;
            $nas->description=$request->description;
            $nas->save();
            return redirect()->back()->with('success','Data Berhasil disimpan');
        }else{
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
    }
}


