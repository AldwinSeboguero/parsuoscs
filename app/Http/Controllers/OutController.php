<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }


    public function index()
    {

      return redirect('/');

    }
}
