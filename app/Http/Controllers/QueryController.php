<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Company, Country};

class QueryController extends Controller
{
    private $country;//declared as private since no other class would inherit from this class

    public function __construct(Country $country)
    {
        $this->country = $country;
    }
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCountry(Country $country)
    {
        $country = $this->country->where('id', $country->id)
            ->with('companies', function ($q){
                return $q->with('users');
            })->first();
        return response()->json([
            "message" => "success",
            "data" => compact('country')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function showCountryUsers(Country $country)
    {
        $users = $country->companyUsers()->paginate(10)->toArray();
        $users['data'] = User::hydrate($users['data']);
        return response()->json([
            "message" => "success",
            "data" => compact('country', 'users')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCompanies()
    {
        $companies = Company::with('users')
            ->get();
        return response()->json([
            "message" => "success",
            "data" => compact('companies')
        ], 200);
    }



}
