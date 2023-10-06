<?php

namespace App\Http\Controllers;

use App\Models\NotesModel;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public static function index(Request $request)
    {
        return NotesModel::getNotes();
    }
}
