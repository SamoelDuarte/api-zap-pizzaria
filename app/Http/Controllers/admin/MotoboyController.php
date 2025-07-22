<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Motoboy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotoboyController extends Controller
{
    public function index()
    {
        $motoboys = Motoboy::all();
        return view('admin.motoboy.index', compact('motoboys'));
    }

    public function create()
    {
        return view('admin.motoboy.create');
    }
    public function get(): JsonResponse
    {
        $motoboys = Motoboy::select('id', 'name', 'phone')->get();

        return response()->json($motoboys);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        Motoboy::create($request->all());

        return redirect()->route('admin.motoboy.index')->with('success', 'Motoboy criado com sucesso.');
    }

    public function edit(Motoboy $motoboy)
    {
        return view('admin.motoboy.edit', compact('motoboy'));
    }

    public function update(Request $request, Motoboy $motoboy)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        $motoboy->update($request->all());

        return redirect()->route('admin.motoboy.index')->with('success', 'Motoboy atualizado com sucesso.');
    }

    public function destroy(Motoboy $motoboy)
    {
        $motoboy->delete();
        return redirect()->route('admin.motoboy.index')->with('success', 'Motoboy removido com sucesso.');
    }
}
