<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Klub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use DB;
use Session;

class HasilController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $data   = DB::select("
                        SELECT 
                            a.id,
                            a.nama_klub,
                            SUM(if(a.id = b.klub_a, 1, 0)) + SUM(if(a.id = c.klub_b, 1, 0)) AS main,
                            SUM(if(b.nilai_a = 3, 1, 0)) + SUM(if(c.nilai_b = 3, 1, 0))  AS menang,
                            SUM(if(b.nilai_a = 1, 1, 0)) + SUM(if(c.nilai_b = 1, 1, 0))  AS seri,
                            SUM(if(b.nilai_a = 0, 1, 0)) + SUM(if(c.nilai_b = 0, 1, 0))  AS kalah,
                            SUM(if(b.nilai_a = 3, b.score_a, 0)) + SUM(if(c.nilai_b = 3, c.score_b, 0))  AS goal_menang,
                            SUM(if(b.nilai_a = 0, b.score_b, 0)) + SUM(if(c.nilai_b = 0, c.score_a, 0))  AS goal_kalah,
                            SUM(if(b.nilai_a = 3, b.nilai_a, 0)) + SUM(if(c.nilai_b = 3, c.nilai_b, 0)) + SUM(if(b.nilai_a = 1, b.nilai_a, 0)) + SUM(if(c.nilai_b = 1, c.nilai_b, 0)) AS poin
                        FROM 
                            `klubs` as a
                            LEFT JOIN hasils b ON a.id = b.klub_a
                            LEFT JOIN hasils c ON a.id = c.klub_b
                        GROUP BY a.id
                        ORDER BY poin DESC
                    ");

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }

        $data['klub'] = Klub::get();
        return view('hasil.index')->with($data);
    }

    public function create_multiple()
    {
        $data['klub'] = Klub::get();
        return view('hasil.form_multiple')->with($data);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
                        'klub_a'    => 'required',
                        'klub_b'    => 'required',
                        'score_a'   => 'required',
                        'score_b'   => 'required'
                    ]);
      
        if ($validator->passes()) {
            if ( $req->score_a ==  $req->score_b) {
                $nilai_a = 1;
                $nilai_b = 1;
            } else {
                if ($req->score_a > $req->score_b) {
                    $nilai_a = 3;
                    $nilai_b = 0;
                } else {
                    $nilai_a = 0;
                    $nilai_b = 3;
                }
                
            }

            Hasil::updateOrCreate([
                'id'        => $req->hasil_id
            ],
            [
                'klub_a'    => $req->klub_a, 
                'klub_b'    => $req->klub_b,
                'score_a'   => $req->score_a, 
                'score_b'   => $req->score_b,
                'nilai_a'   => $nilai_a, 
                'nilai_b'   => $nilai_b
            ]); 

            return response()->json(['success'=>'Hasil berhasil di simpan.']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function cek_data(Request $req)
    {
        if ($req->ajax()) {
            $cek = Hasil::where('klub_a', $req->klub_a)
                        ->where('klub_b', $req->klub_b)
                        ->first();

            if($cek){
                return response()->json(['type' => 'success', 'message' => "Data Klub A Vs Klub B sudah ada ...!"]);
            }else{
                return response()->json(['type' => 'erorr', 'message' => "Tidak ada"]);
            }
        } else {
            return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
        }
    }

    public function simpan(Request $request)
    {
        DB::beginTransaction();
        try {
            $no	= 0;
                    
            foreach($_POST['klub_a'] as $k)
            {
                if( ! empty($k))
                {
                    if ( $_POST['score_a'][$no] ==  $_POST['score_b'][$no]) {
                        $nilai_a = 1;
                        $nilai_b = 1;
                    } else {
                        if ($_POST['score_a'][$no] >  $_POST['score_b'][$no]) {
                            $nilai_a = 3;
                            $nilai_b = 0;
                        } else {
                            $nilai_a = 0;
                            $nilai_b = 3;
                        }
                        
                    }
                    
                    Hasil::create([
                        'klub_a'        => $_POST['klub_a'][$no],
                        'klub_b'   	    => $_POST['klub_b'][$no],
                        'score_a'   	=> $_POST['score_a'][$no],
                        'score_b'   	=> $_POST['score_b'][$no],
                        'nilai_a'   	=> $nilai_a,
                        'nilai_b'   	=> $nilai_b,
                    ]);
                }
                $no++;
            }

            DB::commit();
            return redirect('/hasil');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('server_error', $th->getMessage());
            return redirect('/hasil');
        }
    }

}