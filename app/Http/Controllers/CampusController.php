<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campus;
use App\College;
use App\SignatoryV2;
use Illuminate\Support\Facades\Auth; 
class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
     public function index(Request $request)
     {
         return response()->json(['campuses'=> Campus::when(!Auth::user()->hasRole("admin") && !$request->byPass, function($q) use($request){
             $q->whereIn('id',SignatoryV2::where('user_id',Auth::user()->id)->where('semester_id','>=',8)->distinct('campus_id')->get('campus_id'));
         })
         ->get()],200);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
