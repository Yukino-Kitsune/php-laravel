<?php

namespace App\Http\Controllers;

use App\Models\NotesModel;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    public static function index(Request $request) // TODO implement pagination
    {
        return new Response(NotesModel::getNotes());
    }

    public static function getNote(int $id)
    {
        $note = NotesModel::find($id);
        if($note == null) {
            return new Response('Note not found',
                404,
                ['Content-Type' => 'plain/text']);
        }
        return new Response($note, 200);
    }

    public static function store(Request $request)
    {
        $note = new NotesModel();
        $validation = Validator::make($request->all(), [
            'full_name' => 'required|max:255',
            'company' => 'max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => 'required|max:255',
            'birthday' => 'date',
            'photo' => 'file' // TODO
        ]);
        if($validation->fails()) { // INFO Не смог сделать нормальный вывод ошибки.
            return new Response('Bad request. Check request body. Full name, phone, email are required.',
                400,
                ['Content-Type' => 'plain/text']);
        }
        $note->full_name = $request->full_name;
        $note->company = $request->company;
        $note->email = $request->email;
        $note->phone = $request->phone;
        $note->birthday = $request->birthday;
        $note->photo = null; // TODO FIX ME
        // TODO do something with photo
        try {
            $note->save();
        } catch (QueryException $exception) {
            return new Response('Unexpected error. Please contact with administrator',
                400,
                ['Content-Type' => 'plain/text']);
        }
        return new Response('Note added successfully.', 201);
    }

    public static function update(Request $request, int $id)
    {
        $note = NotesModel::find($id);
        if ($note == null) {
            return new Response('Note not found',
                404,
                ['Content-Type' => 'plain/text']);
         }
        $validation = Validator::make($request->all(), [
            'full_name' => 'max:255',
            'company' => 'max:255',
            'email' => 'email:rfc|max:255',
            'phone' => 'max:255',
            'birthday' => 'date',
            'photo' => '' // TODO
        ]);
        if($validation->fails()) { // INFO Не смог сделать нормальный вывод ошибки.
            return new Response('Bad request. Check request body.',
                400,
                ['Content-Type' => 'plain/text']);
        }
        $note->full_name = $request->full_name != null ? $request->full_name : $note->full_name;
        $note->company = $request->company != null ? $request->company : $note->company;
        $note->email = $request->email != null ? $request->email : $note->email;
        $note->phone = $request->phone != null ? $request->phone : $note->phone;
        $note->birthday = $request->birthday != null ? $request->birthday : $note->birthday;
        // TODO add photo
        return new Response('Note added successfully.', 200);
    }

    public static function delete(int $id)
    {
        $note = NotesModel::find($id);
        if ($note == null) {
            return new Response('Note not found',
                404,
                ['Content-Type' => 'plain/text']);
        }
        if($note->delete()) {
            return new Response('Note deleted', 200);
        }
        return new Response('Unexpected error. Please contact with administrator', 404);
    }
}
