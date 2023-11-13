<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Pegawai;
use App\Models\Training;
use App\Models\MappingKaryawanTraining;


class TrainingKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('karyawan-training.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Pegawai::get();
        $trainings = Training::get();
        return view('karyawan-training.create', compact('karyawans', 'trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'karyawan' => 'required',
                'trainings' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/training-karyawan/create')
                            ->withErrors($validator)
                            ->withInput();
            }
        
            if(!empty($request->trainings))
            {
                $exists_karyawan_ids = MappingKaryawanTraining::where('pegawai_id', $request->karyawan)
                        ->pluck('id')->toArray();

                if(!empty($exists_karyawan_ids))
                {
                    $delete_karyawans = [];

                    foreach ($exists_karyawan_ids as $exist_id)
                        $delete_karyawans[] = $exist_id;

                    if(!empty($delete_karyawans))
                    {
                        try
                        {
                            MappingKaryawanTraining::destroy($delete_karyawans);
                        }
                        catch(\Exception $e)
                        {
                            DB::rollback();
                            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'A system error has occurred. please try again later. '.$e];
                        }
                    }
                }

                foreach($request->trainings as $training)
                {
                    $newMapping = new MappingKaryawanTraining();
                    $newMapping->pegawai_id    = $request->karyawan;
                    $newMapping->training_id   = $training;
                    $newMapping->save();
                }
            }

            if ($newMapping) {
                DB::commit();
                return redirect('/training-karyawan')->withInput();
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
        $karyawans = Pegawai::get();
        $pegawai = Pegawai::find($id);
        $trainings = Training::get();

        $pegawais_selected = array_column($pegawai->mappings->toArray(), 'training_id');
        // dd($pegawais_selected);
        return view('karyawan-training.create', compact(['karyawans','pegawai','trainings', 'pegawais_selected']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'karyawan' => 'required',
                'trainings' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/training-karyawan/create')
                            ->withErrors($validator)
                            ->withInput();
            }
        
            if(!empty($request->trainings))
            {
                $exists_karyawan_ids = MappingKaryawanTraining::where('pegawai_id', $request->karyawan)
                        ->pluck('id')->toArray();

                if(!empty($exists_karyawan_ids))
                {
                    $delete_karyawans = [];

                    foreach ($exists_karyawan_ids as $exist_id)
                        $delete_karyawans[] = $exist_id;

                    if(!empty($delete_karyawans))
                    {
                        try
                        {
                            MappingKaryawanTraining::destroy($delete_karyawans);
                        }
                        catch(\Exception $e)
                        {
                            DB::rollback();
                            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'A system error has occurred. please try again later. '.$e];
                        }
                    }
                }

                foreach($request->trainings as $training)
                {
                    $newMapping = new MappingKaryawanTraining();
                    $newMapping->pegawai_id    = $request->karyawan;
                    $newMapping->training_id   = $training;
                    $newMapping->save();
                }
            }

            if ($newMapping) {
                DB::commit();
                return redirect('/training-karyawan')->withInput();
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return ['status' => false, 'code' => 'EEC001', 'message' => 'A system error has occurred. please try again later. '.$ex];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mapping = MappingKaryawanTraining::find($id);
        $mapping->delete();
        return true;
    }

    public function getKaryawanList(Request $request)
    {
        $keyword = $request['searchkey'];
        $searchables = ["pegawai_id", "training_id"];
        $pegawais = MappingKaryawanTraining::select()
            ->offset($request['start'])
            ->limit(($request['length'] == -1) ? MappingKaryawanTraining::count() : $request['length'])
            ->when($keyword, function ($query) use ($searchables, $keyword) {
                $query->where(function ($query) use ($searchables, $keyword) {
                    foreach ($searchables as $column) {
                        $query->orWhere($column, 'like', '%' . $keyword . '%');
                    }
                });
            })
            ->with('pegawai')
            ->with('training')
            ->get();
            
        $pegawaisCounter = MappingKaryawanTraining::select()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('pegawai_id', 'like', '%' . $keyword . '%');
            })
            ->count();

        $response = [
            'status'          => true,
            'code'            => '',
            'message'         => '',
            'draw'            => $request['draw'],
            'recordsTotal'    => Pegawai::count(),
            'recordsFiltered' => $pegawaisCounter,
            'data'            => $pegawais,
        ];
        return $response;
    }
}
