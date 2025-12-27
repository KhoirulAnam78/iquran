<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelVersion extends Model
{
    use HasFactory;
    protected $table      = 'model_version';
    protected $primaryKey = 'id_model_version';
    protected $guarded    = ['id_model_version'];
}
