<?php

namespace App\Models;

use Database\Factories\NotesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class NotesModel extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $fillable = ['full_name', 'company', 'email', 'phone', 'photo'];

    protected static function newFactory() : NotesFactory
    {
        return NotesFactory::new();
    }

    public static function getPaginatedNotes(int $pagination)
    {
        return DB::table('notes')->simplePaginate($pagination);
    }

    public static function getAllNotes()
    {
        return DB::table('notes')->get();
    }
}
