<?php

namespace App\Http\Controllers;

use App\Models\Klub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class KlubController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $data = Klub::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm ubahKlub">Ubah</a>';
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm hapusKlub">Hapus</a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('klub.index');
    }

    public function store(Request $req)
    {
        if ($req->klub_id) {
            $validator = Validator::make($req->all(), [
                'nama_klub' => "required|unique:klubs,nama_klub,$req->klub_id",
                'kota'      => 'required'
            ]);
        } else {
            $validator = Validator::make($req->all(), [
                'nama_klub' => 'required|unique:klubs,nama_klub',
                'kota'      => 'required'
            ]);
        }
      
        if ($validator->passes()) {
            Klub::updateOrCreate([
                'id'        => $req->klub_id
            ],
            [
                'nama_klub' => $req->nama_klub, 
                'kota'      => $req->kota
            ]); 

            return response()->json(['success'=>'Klub berhasil di simpan.']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function edit($id)
    {
        $klub   = Klub::find($id);
        return response()->json($klub);
    }

    public function destroy($id)
    {
        Klub::find($id)->delete();
        return response()->json(['success'=>'Klub berhasil di hapus.']);
    }

}