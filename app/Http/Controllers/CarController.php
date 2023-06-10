<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ImageCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ValidationCarRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entities = Car::query();

        if(auth()->user()->role_id>3){
            $entities->publish();
        }

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
        $entity = new Car([
            'description'   => $request->description,
            'model'         => $request->model,
            'year'          => $request->year,
            'color'         => $request->color,
            'lat'           => $request->lat,
            'long'          => $request->long,
            'day_price'     => $request->day_price,
            'km'            => $request->km,
            'registration'  => $request->registration,
            'address'       => $request->address,
            'is_manuel'     => $request->is_manuel,
            'mark_id'       => $request->mark_id,
            'category_id'   => $request->category_id,
            'type_id'       => $request->type_id,
            'state_id'      => 1,
            'created_by'    => auth()->user()->id
        ]);

        DB::transaction(function () use ($request, $entity) {
            $entity->save();

            foreach($request->medias as $media){
                $fileName = env('APP_NAME_MEDIA') .'-'. date('Y-m-d-à-H-i-s') . '.' . $media->getClientOriginalExtension();
                Storage::putFileAs("/cars", $media, $fileName);

                ImageCar::create([
                    'path' => '/storage/cars/'. $fileName,
                    'car_id'  => $entity->id
                ]);
            }
            // notifier les administrateur et le client de l'ajout d'un nouveau véhicule
        });

        $entity = $this->show($entity->id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($car)
    {
        $entities = Car::leftJoin('mark_cars', "cars.mark_id", '=', 'mark_cars.id')
        ->leftJoin('users', "cars.created_by", '=', 'users.id')
        ->leftJoin('category_cars', "cars.category_id", '=', 'category_cars.id')
        ->leftJoin('state_cars', "cars.state_id", '=', 'state_cars.id')
        ->leftJoin('type_cars', "cars.type_id", '=', 'type_cars.id');

        $entities = $entities->where('cars.id', $car)->select(
            "cars.id",
            "cars.description",
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
            "cars.state_id",
            DB::raw('CONCAT(users.firstname, \' \', users.lastname) AS publish_by'),
            "category_cars.name_fr as category_car",
            "state_cars.name_fr as state_car",
            "type_cars.name_fr as type_car",
            "mark_cars.name as mark_car",
        );

        $images = ImageCar::where('car_id', $car)->select('path', 'id')->get();

        return [
            'car'    => $entities->firstOrFail(),
            'images' => $images
        ];
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
    public function update(UpdateCarRequest $request)
    {
        $entity = Car::findOrFail($request->car_id);

        DB::transaction(function () use ($request, $entity) {
            $entity->update([
                'description'   => $request->description,
                'model'         => $request->model,
                'year'          => $request->year,
                'color'         => $request->color,
                'lat'           => $request->lat,
                'long'          => $request->long,
                'day_price'     => $request->day_price,
                'km'            => $request->km,
                'registration'  => $request->registration,
                'address'       => $request->address,
                'is_manuel'     => $request->is_manuel,
                'mark_id'       => $request->mark_id,
                'category_id'   => $request->category_id,
                'type_id'       => $request->type_id,
            ]);

            foreach($request->medias as $media){
                $fileName = env('APP_NAME_MEDIA') .'-'. date('Y-m-d-à-H-i-s') . '.' . $media->getClientOriginalExtension();
                Storage::putFileAs("/cars", $request->file('file'), $fileName);

                ImageCar::create([
                    'path' => '/storage/cars/'. $fileName,
                    'car_id'  => $entity->id
                ]);
            }

            // notifier les administrateur et le client de l'ajout d'un nouveau véhicule
        });

        $entity = $this->show($entity->id);
        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        DB::transaction(function () use ($car) {
            $car->delete();
        });

        return response()->json([
            'success' => true,
            'message' => __('auth.success_message')
        ]);
    }
    
    /**
     * Ajouter media associer a une voiture
     * Fonction test
     */
    public function add_media(Request $request)
    {
        DB::transaction(function () use ($request) {            
            foreach($request->medias as $media){
                $fileName = env('APP_NAME_MEDIA') .'-'. date('Y-m-d-à-H-i-s') . '.' . $media->getClientOriginalExtension();
                Storage::putFileAs("/cars", $media, $fileName);

                ImageCar::create([
                    'path'    => '/storage/cars/'. $fileName,
                    'car_id'  => $request->car_id
                ]);
            }
        });
        $entity = $this->show($request->car_id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Supprimer media associer a une voiture
     */
    public function delete_media(Request $request)
    {
        DB::transaction(function () use ($request) {
            ImageCar::where('id', $request->media)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => __('auth.success_message')
        ]);
    }
    
    /**
     * Rejeter l'ajout d'un véhicule sur la plateforme
     */
    public function publish(Request $request)
    {
        $entity = Car::where('id', $request->car_id)->where('state_id', '>', 2);

        if(auth()->user()->role_id>3){
            $entity->where('created_by', auth()->user()->id);
        }

        $entity = $entity->firstOrFail();
        
        DB::transaction(function () use ($entity) {
            $entity->update([
                'state_id'             => 4
            ]);

            // notifier le client que son véhicule est rejeter sur la plateforme
        });

        $entity = $this->show($entity->id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Rejeter l'ajout d'un véhicule sur la plateforme
     */
    public function review(Request $request)
    {
        $entity = Car::where('id', $request->car_id)->where('state_id', '>', 2);

        if(auth()->user()->role_id>3){
            $entity->where('created_by', auth()->user()->id);
        }

        $entity = $entity->firstOrFail();
        
        DB::transaction(function () use ($entity) {
            $entity->update([
                'state_id'             => 3
            ]);

            // notifier le client que son véhicule est rejeter sur la plateforme
        });

        $entity = $this->show($entity->id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Approuver l'ajout d'un véhicule sur la plateforme
     * Par un Admin
     */
    public function validation(ValidationCarRequest $request)
    {
        $entity = Car::where('state_id', 1)->where('id',$request->car_id)->firstOrFail();

        DB::transaction(function () use ($request, $entity) {
            $entity->update([
                'client_price'          => $request->location_price + $entity->day_price,
                'percentage_reduction'  => $request->percentage_reduction,
                'location_price'        => $request->location_price,
                'state_id'              => 3
            ]);

            // notifier le client que son véhicule est disponible sur la plateforme
        });

        $entity = $this->show($entity->id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }

    /**
     * Rejeter l'ajout d'un véhicule sur la plateforme
     * Par un Admin
     */
    public function revocation(Request $request)
    {
        $entity = Car::where('state_id', 1)->where('id',$request->car_id)->firstOrFail();

        DB::transaction(function () use ($entity) {
            $entity->update([
                'state_id'             => 2
            ]);

            // notifier le client que son véhicule est rejeter sur la plateforme
        });

        $entity = $this->show($entity->id);

        return response()->json([
            'success' => true,
            'data'    => $entity,
            'message' => __('auth.success_message')
        ]);
    }
}
