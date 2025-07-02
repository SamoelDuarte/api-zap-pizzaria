<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitWebhookController extends Controller
{
   public function handle(Request $request)
   {
       $output = [];
       $returnVar = null;
       $projectPath = base_path();

       // Comando para resetar e limpar mudanças locais antes do pull
       $cmd = "cd {$projectPath} && /usr/bin/git reset --hard && /usr/bin/git clean -fd && /usr/bin/git pull origin main 2>&1";

       // Executa o comando
       exec($cmd, $output, $returnVar);

       // Verifica qual usuário o comando está sendo executado
       $executedAs = exec('whoami');

       // Retorna a resposta em formato JSON
       return response()->json([
           'executed_as' => $executedAs,
           'executed_command' => $cmd,
           'output' => $output,
           'return_var' => $returnVar
       ]);
   }
}

