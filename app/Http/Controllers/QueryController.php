<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Company, Country};

class QueryController extends Controller
{
    //declared as private since no other class would inherit from this class
    private $country;
    private $lang;

    public function __construct(Country $country)
    {
        $this->lang = "querycontroller_messages.";
        $this->country = $country;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompaniesUsersByCountry($country_id) 
    {
        try {
            $data = User::has('companies')->with(
                ['companies' => function($c) use($country_id){
                    $c->where('country_id', $country_id)->with('country');
                }]
            )->get();
            $users = [];
            foreach ($data as $d) {
                if (count($d->companies) !== 0) {
                    $users[] = $d;
                }
            }
            
            $country_name = $this->country->where('id', $country_id)->first()->name ?? "";

            if (!empty($country_name) && count($users) == 0) {
                $message = trans($this->lang.'user_associated_with_country_notfound', ['country_name' => $country_name]);
            }else if (empty($country_name)) {
                $message = trans($this->lang.'country_not_found');
            }else{
                $message = trans($this->lang.'success');
            }

            return response()->json([
                "message" => $message,
                "data" => compact('users')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 422);
        }
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
            "message" => trans($this->lang.'success'),
            "data" => compact('country')
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
            "message" => trans($this->lang.'success'),
            "data" => compact('companies')
        ], 200);
    }



}
