<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils;
use App\Models\AvailableSlot;
use App\Models\AvailableSlotConfig;
use App\Models\Config;

class ConfigController extends Controller
{

    public function index(){
        $availableSlots = AvailableSlotConfig::all();
        $config = Config::firstOrFail(); 
        return view('admin.config.index', compact('config'))->with('availability', $availableSlots);
      
    }
    public function edit()
    {
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config
        return view('config', compact('config'));
    }

    public function update(Request $request)
    {

       
    
        $config = Config::firstOrFail(); // Supondo que você tenha apenas uma entrada na tabela config

      
        $config->status = $request->has('status');
        $config->chatbot = $request->has('chatbot');
        
        // Nome da pizzaria
        $config->nome_pizzaria = $request->input('nome_pizzaria');
        
        // Processar telefone da pizzaria com código do país
        if ($request->filled('telefone')) {
            $telefone = preg_replace('/\D/', '', $request->input('telefone')); // Remove todos os não dígitos
            
            // Garantir que sempre comece com 55
            if (!str_starts_with($telefone, '55')) {
                $telefone = '55' . $telefone;
            }
            
            $config->telefone = $telefone;
        }
        
        // Campos de endereço
        $config->cep = $request->input('cep');
        $config->endereco = $request->input('endereco');
        $config->numero = $request->input('numero');
        $config->complemento = $request->input('complemento');
        $config->bairro = $request->input('bairro');
        $config->cidade = $request->input('cidade');
        $config->estado = $request->input('estado');
        
        $config->save();

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
