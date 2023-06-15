<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\CurentTenantMessageResource;
use Illuminate\Http\Request;

class MessageController extends Controller
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
    public function store(StoreMessageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }


    public function comment(Request $request)
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
            $CurentTenant = new Message();
            $CurentTenant->sender = $request->sender;
            $CurentTenant->receiver = $request->receiver;
            $CurentTenant->curent_tenant_id = $request->curent_tenant_id;
            $CurentTenant->message = $request->message;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantMessageResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }


    public function updatecomment(Request $request,$id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $message = Message::find($id);
            $all = null;
            if (is_null($message)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $message->message = $request->message;
            $message->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantMessageResource($message), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }


    public function deletecomment($id)
    {
        $args = [];
        $args['error'] = false;
        DB::beginTransaction();

        try {
            $message = Message::find($id);
            $all = null;
            if (is_null($message)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $message->delete();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantMessageResource($message), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }
}
