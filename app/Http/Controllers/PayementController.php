<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayementRequest;
use App\Http\Requests\UpdatePayementRequest;
use App\Models\Payement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class PayementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePayementRequest $request)
    {

        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'sender' => 'required|exists:users,id',
            'receiver' => 'required|exists:users,id',
            'curent_tenant_id' => 'required|exists:curent_tenants,id',
            'message' => 'required',
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $payement = new Payement();
            $payement->save();
            // Commit the transaction
            DB::commit();
            return response()->json([
                'success' => true,
                'data'    => $payement,
                'message' => __('auth.success_message')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Payement $payement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payement $payement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayementRequest $request, Payement $payement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payement $payement)
    {
        //
    }
}
