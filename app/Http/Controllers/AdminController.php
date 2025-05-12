<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Write code on method
     *
     * @return response()
     */
    public function crud()
    {
        return view('books.index');
    }
}
