<?php

namespace App\Http\Controllers;

use App\Models\TableMatch;
use Illuminate\Http\Request;

class TableMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scores=TableMatch::all();

        return view('scores.index', compact('scores'));
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, TableMatch $tableMatch)
    {
        //
    }
    public function destroy(TableMatch $tableMatch)
    {
        //
    }
}
