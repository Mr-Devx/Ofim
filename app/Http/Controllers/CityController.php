<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;

use Illuminate\Http\Request;
use App\Http\Resources\CityResource;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CityResource::collection(
            City::orderBy('created_at', 'DESC')->get()
        );
    }

    public function citiesCountry($countryId)
    {
        $country = Country::find($countryId);

        if (!$country) {
            return 'Pays non trouvé'; // Gérez le cas où le pays n'est pas trouvé.
        }
    
        $cities = $country->cities;
    
        return $cities;
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
}
