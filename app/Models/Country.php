<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
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
     * Returns all companies for the country .
     *
     * @return Illuminate/Database/Eloquent/Relations/Relation
     */

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Returns all users belonging to the country through company association.
     *
     * @return Illuminate/Database/Eloquent/Relations/Relation
     */

    public function companyUsers()
    {
        return DB::table('countries')
            ->join('companies', 'companies.country_id', '=', 'countries.id')
            ->join('company_user', 'company_user.company_id', '=', 'companies.id')
            ->join('users', 'users.id', '=', 'company_user.user_id')
            ->select('users.*')
            ->where('countries.id', $this->id);
    }

    /**
     * undocumented class
     * 
     * @return string
     */  

    public function getRouteKeyName()
    {
        return 'name';
    }
}
