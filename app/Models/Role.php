<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ["name"];


    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
