<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurentTenantRequest;
use App\Http\Requests\UpdateCurentTenantRequest;
use App\Models\CurentTenant;
use App\Helpers\CustomPaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CurentTenantResource;
use Illuminate\Support\Facades\Schema;

class CurentTenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CurentTenant::query();

        // Récupérer tous les paramètres de la requête GET
        $filtres = $request->input();
        // Filtrer les résultats en fonction des paramètres
        foreach ($filtres as $nomFiltre => $valeurFiltre) {
            // Vérifier si le paramètre de filtre correspond à un attribut de la table
            if (Schema::hasColumn((new CurentTenant)->getTable(), $nomFiltre)) {
                $query->where($nomFiltre, $valeurFiltre);
            }
        }

        // Pagination avec CustomPaginator
        $resultats = $query->paginate(env('APP_PAGINATE'));
        $customPaginator = new CustomPaginator($resultats->items(), $resultats->total(), $resultats->perPage(), $resultats->currentPage());

        // Retourner les résultats paginés avec les informations de pagination personnalisées
        return response()->json($customPaginator->toArray());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'created_by' => 'required|exists:users,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $CurentTenant = new CurentTenant();
            $CurentTenant->car_id = $request->car_id;
            $CurentTenant->created_by = $request->created_by;
            $CurentTenant->start_date = $request->start_date;
            $CurentTenant->end_date = $request->end_date;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
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
    public function show(CurentTenant $curentTenant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $CurentTenant = CurentTenant::find($id);
            $all = null;
            if (is_null($CurentTenant)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $CurentTenant->start_date = $request->start_date;
            $CurentTenant->end_date = $request->end_date;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CurentTenant $curentTenant)
    {
        //
    }


    public function note(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'curent_note' => 'required|integer'
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $CurentTenant = CurentTenant::find($id);
            $all = null;
            if (is_null($CurentTenant)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $CurentTenant->curent_note = $request->curent_note;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }



    public function extend(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $CurentTenant = CurentTenant::find($id);
            $all = null;
            if (is_null($CurentTenant)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $CurentTenant->start_date = $request->start_date;
            $CurentTenant->end_date = $request->end_date;
            $CurentTenant->is_an_extension = true;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }




    public function cancel($id)
    {
        DB::beginTransaction();

        try {
            $CurentTenant = CurentTenant::find($id);
            $all = null;
            if (is_null($CurentTenant)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $CurentTenant->state = false;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }

    public function valid($id)
    {
        DB::beginTransaction();

        try {
            $CurentTenant = CurentTenant::find($id);
            $all = null;
            if (is_null($CurentTenant)) {
                $args['error'] = true;
                $args['message'] = 'Ressource introuvable';
                return response()->json($args, 404);
            }
            $CurentTenant->state = true;
            $CurentTenant->save();
            // Commit the transaction
            DB::commit();
            return response()->json(new CurentTenantResource($CurentTenant), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }
    }
}
