<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entities = Driver::query()->leftJoin('users', "drivers.owner_id", '=', 'users.id');

        $entities->where('owner_id', auth()->user()->id);

        if($request->owner && $request->owner>0){
            $entities->where('drivers.owner_id', $request->owner);
        }

        if($request->fullname && $request->fullname!=""){
            $entities->where('drivers.fullname', 'like',  '%'.$request->fullname.'%');
        }

        if($request->license_number && $request->license_number!=""){
            $entities->where('drivers.license_number', 'like',  '%'.$request->license_number.'%');
        }

        $entities = $entities->select("drivers.*");

        $entities->addSelect(
            DB::raw('CONCAT(users.firstname, \' \', users.lastname) AS owner')
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
    public function store(StoreDriverRequest $request)
    {
        $entity = new Driver([
            "fullname"      => $request->fullname,
            "phone"         => $request->phone,
            "license_number"      => $request->license_number,
            "license_expire_date" => $request->license_expire_date,
            "owner_id"      => auth()->user()->id,
        ]);

        DB::transaction(function () use ($entity, $request) {
            $fileName = env('APP_NAME_MEDIA') .'-'. date('Y-m-d-à-H-i-s') . '.' . $request->license_file->getClientOriginalExtension();
            Storage::putFileAs("/cars", $request->license_file, $fileName);
            $entity->license_path = '/storage/cars/'. $fileName;

            $entity->save();
        });

        return response()->json([
            'driver'  => $entity,
            'success' => true,
            'message' => __('messages.success_message'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $data = Driver::query()->leftJoin('users', "drivers.owner_id", '=', 'users.id');
        
        $data->where('drivers.id', $driver->id);

        $data->select("drivers.*");

        $data->addSelect(
            DB::raw('CONCAT(users.firstname, \' \', users.lastname) AS owner')
        );

        return $data->firstOrFail();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request)
    {
        $driver = Driver::findOrFail($request->driver_id);

        DB::transaction(function () use ($driver, $request) {
            $license_path = $driver->license_path;

            if($request->license_file){
                $fileName = env('APP_NAME_MEDIA') .'-'. date('Y-m-d-à-H-i-s') . '.' . $request->license_file->getClientOriginalExtension();
                Storage::putFileAs("/cars", $request->license_file, $fileName);
                $license_path = '/storage/cars/'. $fileName;
            }

            $driver->update([
                "fullname"      => $request->fullname,
                "phone"         => $request->phone,
                "license_number"      => $request->license_number,
                "license_expire_date" => $request->license_expire_date,
                'license_path'        => $license_path
            ]);
        });

        return response()->json([
            'driver'  => $driver,
            'success' => true,
            'message' => __('messages.success_message'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        DB::transaction(function () use ($driver) {
            $driver->delete();
        });

        return response()->json([
            'success' => true,
            'message' => __('messages.success_message')
        ]);
    }
}
