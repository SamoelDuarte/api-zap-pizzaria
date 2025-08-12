<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Verifica se é terça-feira (2 = terça-feira)
        if (now()->dayOfWeek === 2) {
            return view('front.checkout.closed', [
                'message' => 'Desculpe, não atendemos às terças-feiras. Voltaremos amanhã!'
            ]);
        }
        return view('front.home.index');
    }
   
}
