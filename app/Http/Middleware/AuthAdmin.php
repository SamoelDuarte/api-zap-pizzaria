<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    protected $allowedRoles;
    
    function __construct()
    {
        $this->allowedRoles = ['admin', 'user'];
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('authenticated') || !session('userData') || !is_object(session('userData')) || session('userData')->role != "admin" ) {
            return redirect('/admin/login');
        }

        $userData = session('userData');
        $freshUser = User::where('id', $userData->id)->where('active', 1)->first();

        if (!$freshUser) {
            // Limpar a sessão se o usuário não existe ou está inativo
            session()->flush();
            return redirect('/admin/login');
        }

        if (!in_array($freshUser->role, $this->allowedRoles)) {
            // Limpar a sessão se o usuário não tem permissão
            session()->flush();
            return redirect('/admin/login');
        }

        session([
            'userData' => $freshUser
        ]);

        return $next($request);
    }
}