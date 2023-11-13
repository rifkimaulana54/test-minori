<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Training;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('training.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'jenis' => 'required',
                'tgl_sertifikat' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/training/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            $newTraining = Training::create([
                'jenis'          => $request->jenis,
                'tgl_sertifikat' => date('Y-m-d H:i:s', strtotime($request->tgl_sertifikat)),
                'keterangan'     => $request->keterangan,
            ]);
            
            if ($newTraining) {
                DB::commit();
                return redirect('/karyawan')->withInput();
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return ['status' => false, 'code' => 'EEC001', 'message' => 'A system error has occurred. please try again later. '.$ex];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
