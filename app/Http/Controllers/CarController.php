<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entities = Car::publish();
        // ->leftJoin('mark_cars', "cars.mark_id", '=', 'mark_cars.id')
        // ->leftJoin('users', "cars.created_by", '=', 'users.id')
        // ->leftJoin('category_cars', "cars.category_id", '=', 'category_cars.id')
        // ->leftJoin('state_cars', "cars.state_id", '=', 'state_cars.id')
        // ->leftJoin('type_cars', "cars.type_id", '=', 'type_cars.id');

        if($request->mark && $request->mark>0){
            $entities->where('cars.mark_id', $request->mark);
        }
        
        if($request->publish_by && $request->publish_by>0){
            $entities->where('cars.publish_by', $request->publish_by);
        }

        if($request->category && $request->category>0){
            $entities->where('cars.category_id', $request->category);
        }

        if($request->state && $request->state>0){
            $entities->where('cars.state_id', $request->state);
        }

        if($request->type && $request->type>0){
            $entities->where('cars.type_id', $request->type);
        }
        
        if($request->order_by && $request->order_by_dir){
            $entities->orderBy('cars.'.$request->order_by, $request->order_by_dir);
        }else{
            $entities->orderBy('cars.year', 'asc');
        }
        
        $entities = $entities->select(
            "cars.id",
            "cars.model",
            "cars.lat",
            "cars.long",
            "cars.client_price",
            "cars.km",
            "cars.is_manuel",
            "cars.created_at",
        );

        return $entities->paginate(env('APP_PAGINATE'));
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
    public function store(StoreCarRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($car)
    {
        $entities = Car::publish()->leftJoin('mark_cars', "cars.mark_id", '=', 'mark_cars.id')
        ->leftJoin('users', "cars.created_by", '=', 'users.id')
        ->leftJoin('category_cars', "cars.category_id", '=', 'category_cars.id')
        ->leftJoin('state_cars', "cars.state_id", '=', 'state_cars.id')
        ->leftJoin('type_cars', "cars.type_id", '=', 'type_cars.id');

        $entities = $entities->where('cars.id', $car)->select(
            "cars.id",
            "cars.decription",
            "cars.model",
            "cars.year",
            "cars.color",
            "cars.lat",
            "cars.long",
            "cars.client_price",
            "cars.note",
            "cars.km",
            "cars.registration",
            "cars.is_manuel",
            "cars.created_at",
            DB::raw('CONCAT(users.firstname, \' \', users.lastname) AS publish_by'),
            "category_cars.name as category_car",
            "state_cars.name as state_car",
            "type_cars.name as type_car",
        );

        return $entities->firstOrFail();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
