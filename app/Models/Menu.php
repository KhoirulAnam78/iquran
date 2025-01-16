<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_menu_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(Menu::class, 'parent_menu_id', 'id')->orderBy('position', 'ASC');
    }
}
