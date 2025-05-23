<?php

namespace App\Http\Controllers;

use App\Http\Resources\nilaiResource;
use App\Models\nilaiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class nilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $nim = $request->query('nim');


        if ($nim) {
            $nilai = nilaiService::where('nim', $nim)->get();
        } else {
            $nilai = nilaiService::all();
        }

        return new nilaiResource($nilai, 'success', $nim ? "Daftar nilai mahasiswa NIM $nim" : "Daftar semua nilai");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'kode_mk' => 'required',
            'nilai' => 'required|integer|min:0|max:100'
        ]);
        if ($validator->fails()) {
            return new nilaiResource(null, 'failed', $validator->errors());
        };

        // $mahasiswa = nilaiService::create($request->all());
        // return new nilaiResource($mahasiswa, 'success', 'nilai create successfully');

        // $response = Http::get("http://localhost:8000/api/mahasiswa/nim/" . $request->nim);

        // Ver Docker
        $response = Http::get(env('MAHASISWA_SERVICE_URL') . '/api/mahasiswa/nim/' . $request->nim);

        if ($response->status() !== 200 || $response->json('data') === null) {
            return new nilaiResource(null, 'failed', 'Mahasiswa tidak ditemukan di MahasiswaService');
        }

        $nilai = nilaiService::create($request->all());

        Redis::publish('nilai_ditambahkan', json_encode([
            'nim' => $request->nim,
            'kode_mk' => $request->kode_mk,
            'nilai' => $request->nilai
        ]));

        return new nilaiResource($nilai, 'success', 'Nilai berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'kode_mk' => 'required',
            'nilai' => 'required|integer|min:0|max:100'
        ]);
        if ($validator->fails()) {
            return new nilaiResource(null, 'failed', $validator->errors());
        };

        $nilai = nilaiService::find($id);
        if (!$nilai) {
            return new nilaiResource(null, 'failed', 'nilai tidak ditemukan');
        }

        // $response = Http::get("http://localhost:8000/api/mahasiswa/nim/" . $request->nim);

        // Ver Docker
        $response = Http::get(env('MAHASISWA_SERVICE_URL') . '/api/mahasiswa/nim/' . $request->nim);

        if ($response->status() !== 200 || $response->json('data') === null) {
            return new nilaiResource(null, 'failed', 'Mahasiswa dengan NIM tersebut tidak ditemukan');
        }
        $nilai->update($request->all());
        Redis::publish('nilai_update', json_encode([
            'nim' => $request->nim,
            'kode_mk' => $request->kode_mk,
            'nilai' => $request->nilai
        ]));


        return new nilaiResource($nilai, 'success', 'nilai terupdate');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nilai = nilaiService::find($id);
        if (!$nilai) {
            return new nilaiResource(null, 'Failed', 'Nilai tidak ditemukan');
        }
        $nilai->delete();
        return new nilaiResource(null, 'success', 'Nilai berhasil dihapus');
    }
}
