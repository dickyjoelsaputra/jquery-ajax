<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EmployeeAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Employee::orderBy('name', 'asc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('button', ['data' => $data]);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required'
        ], [
            'name.required' => 'tolong isi nama',
            'email.required' => 'tolong isi email',
            'email.email' => 'email wajib benar'
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        } else {
            Employee::create($request->all());
            return response()->json(['success' => "berhasil menyimpan data"]);
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
        $data = Employee::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required'
        ], [
            'name.required' => 'tolong isi nama',
            'email.required' => 'tolong isi email',
            'email.email' => 'email wajib benar'
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        } else {
            $data = Employee::findOrFail($id);
            $data->update($request->all());
            return response()->json(['success' => "berhasil menyimpan data"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $data = Employee::findOrFail($id);
        $data->delete();
    }
}
