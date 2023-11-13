<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Pegawai;
use App\Models\Training;
use App\Models\MappingKaryawanTraining;


class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('karyawan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Pegawai::get();
        $trainings = Training::get();
        return view('karyawan.create', compact('karyawans', 'trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'nip' => 'required|unique:pegawais|numeric',
                'nama_karyawan' => 'required|max:255',
                'jabatan' => 'required|max:255',
                // 'trainings' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/karyawan/create')
                            ->withErrors($validator)
                            ->withInput();
            }

            $newKaryawan = Pegawai::create([
                'nip'           => $request->nip,
                'nama_karyawan' => $request->nama_karyawan,
                'jabatan' => $request->jabatan,
            ]);

            if(!empty($request->trainings))
            {
                foreach($request->trainings as $training)
                {
                    $newMapping = new MappingKaryawanTraining();
                    $newMapping->pegawai_id    = $newKaryawan->id;
                    $newMapping->training_id   = $training;
                    $newMapping->save();
                }
            }

            if ($newKaryawan) {
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
        $karyawans = Pegawai::get();
        $pegawai = Pegawai::find($id);
        $trainings = Training::get();

        $pegawais_selected = array_column($pegawai->mappings->toArray(), 'training_id');
        return view('karyawan.create', compact(['karyawans','pegawai','trainings', 'pegawais_selected']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'nip' => 'required|numeric',
                'nama_karyawan' => 'required|max:255',
                'jabatan' => 'required|max:255',
                // 'trainings' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/karyawan/'.$request->id)
                            ->withErrors($validator)
                            ->withInput();
            }

            $karyawan = Pegawai::where('id', $request->id)->first();
            $karyawan->nip = $request->nip;
            $karyawan->nama_karyawan = $request->nama_karyawan;
            $karyawan->jabatan = $request->jabatan;
            $karyawan->save();

            if(!empty($request->trainings))
            {
                $exists_karyawan_ids = MappingKaryawanTraining::where('pegawai_id', $karyawan->id)
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
                    $newMapping->pegawai_id    = $karyawan->id;
                    $newMapping->training_id   = $training;
                    $newMapping->save();
                }
            }

            if ($karyawan) {
                DB::commit();
                return redirect('/karyawan')->withInput();
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
        $pegawai = Pegawai::find($id);
        $pegawai->delete();
        $exists_karyawan_ids = MappingKaryawanTraining::where('pegawai_id', $id)
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
        return true;
    }

    public function getKaryawanList(Request $request)
    {
        $keyword = $request['searchkey'];
        $searchables = ["nip", "nama_karyawan", "jabatan"];
        $pegawais = Pegawai::select('pegawais.*')
            ->leftJoin('mapping_training_pegawais', 'pegawais.id', '=', 'mapping_training_pegawais.pegawai_id')
            ->offset($request['start'])
            ->limit(($request['length'] == -1) ? Pegawai::count() : $request['length'])
            ->when($keyword, function ($query) use ($searchables, $keyword) {
                $query->where(function ($query) use ($searchables, $keyword) {
                    foreach ($searchables as $column) {
                        $query->orWhere($column, 'like', '%' . $keyword . '%');
                    }
                });
            })
            ->groupBy('pegawais.id')
            ->with('mappings')
            ->with('mappings.training')
            ->get();
            
        $pegawaisCounter = Pegawai::select()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nip', 'like', '%' . $keyword . '%');
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
