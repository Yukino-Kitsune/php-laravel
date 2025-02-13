<?php

namespace App\Http\Controllers;

use App\Models\NotesModel;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    public static function index(Request $request)
    {
        if($request->has('all')) {
            return new Response(NotesModel::getAllNotes());
        }
        $validation = Validator::make($request->all(), [
            'page' => 'int',
            'pagination' => 'int',
        ]);
        if($validation->fails()) { // INFO Не смог сделать нормальный вывод ошибки.
            return new Response($validation->messages()->toJson(),
                400,
                ['Content-Type' => 'application/json']);
        }
        $pagination = $request->get('pagination') != null ? $request->get('pagination') : 15;
        return new Response(NotesModel::getPaginatedNotes($pagination));
    }

    public static function getNote($id)
    {
        if(!is_numeric($id)) {
            return new Response('id is not an integer',
                400,
                ['Content-Type' => 'plain/text']);
        }
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
            'photo' => 'image'
        ]);
        if($validation->fails()) { // INFO Не смог сделать нормальный вывод ошибки.
            return new Response($validation->messages()->toJson(),
                400,
                ['Content-Type' => 'application/json']);
        }
        $note->full_name = $request->full_name;
        $note->company = $request->company;
        $note->email = $request->email;
        $note->phone = $request->phone;
        $note->birthday = $request->birthday;
        $path = $request->file('photo')->store('/photos');
        $note->photo = Storage::url($path);
        try {
            $note->save();
        } catch (QueryException $exception) {
            Storage::delete($path);
            return new Response('Unexpected error. Please contact with administrator',
                400,
                ['Content-Type' => 'plain/text']);
        }
        return new Response('Note added successfully.', 201);
    }

    public static function update(Request $request, $id)
    {
        if(!is_numeric($id)) {
            return new Response('id is not an integer',
                400,
                ['Content-Type' => 'plain/text']);
        }
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
            'photo' => 'image'
        ]);
        if($validation->fails()) { // INFO Не смог сделать нормальный вывод ошибки.
            return new Response($validation->messages()->toJson(),
                400,
                ['Content-Type' => 'application/json']);
        }
        $note->full_name = $request->full_name != null ? $request->full_name : $note->full_name;
        $note->company = $request->company != null ? $request->company : $note->company;
        $note->email = $request->email != null ? $request->email : $note->email;
        $note->phone = $request->phone != null ? $request->phone : $note->phone;
        $note->birthday = $request->birthday != null ? $request->birthday : $note->birthday;
        if($request->hasFile('photo')) {
            $path = $request->file('photo')->store('/photos');
            $note->photo = url(Storage::url($path));
        }
        try {
            $note->save();
        } catch (QueryException $exception) {
            if(isset($path)) {
                Storage::delete($path);
            }
            return new Response('Unexpected error. Please contact with administrator',
                400,
                ['Content-Type' => 'plain/text']);
        }
        return new Response('Note edit successfully.', 200);
    }

    public static function delete($id)
    {
        if(!is_numeric($id)) {
            return new Response('id is not an integer',
                400,
                ['Content-Type' => 'plain/text']);
        }
        $note = NotesModel::find($id);
        if ($note == null) {
            return new Response('Note not found',
                404,
                ['Content-Type' => 'plain/text']);
        }
        if($note->delete()) {
            Storage::delete($note->photo); // Если файла нет на сервере, исключение не вылетает
            return new Response('Note deleted', 200);
        }
        return new Response('Unexpected error. Please contact with administrator', 404);
    }
}
