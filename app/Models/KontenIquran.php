<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenIquran extends Model
{
    use HasFactory;
    protected $table      = 'konten_iquran';
    protected $primaryKey = 'id_konten';
    protected $guarded    = ['id_konten'];
}
