<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Returns company country.
     *
     * @return Illuminate/Database/Eloquent/Relations/Relation
     */

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    /**
     * Returns all company users.
     *
     * @return Illuminate/Database/Eloquent/Relations/Relation
     */

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }   
}
