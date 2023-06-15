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
use Illuminate\Support\Facades\Http;
use App\Models\Car;
use Illuminate\Support\Str;
use App\Models\Payement;
use GuzzleHttp\Client;

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
            $transaction_id = Str::random(40);
            $car =  Car::find($request->car_id);
            $CurentTenant = new CurentTenant();
            $CurentTenant->car_id = $request->car_id;
            $CurentTenant->created_by = $request->created_by;
            $CurentTenant->start_date = $request->start_date;
            $CurentTenant->end_date = $request->end_date;
            $CurentTenant->save();

            $Payement = new Payement();
            $Payement->created_by = $request->created_by;
            $Payement->code_payement = $transaction_id;
            $Payement->means_of_payment = "MOBILE";
            $Payement->amount = $car->client_price;
            $Payement->save();
            CurentTenant::where('id', $CurentTenant->id)->update([ 'payement_id' => $Payement->id]);
            $api_key = env('CINETPAY_API_KEY');
            $site_id = env('CINETPAY_SITE_ID');
            $api_url = "https://api-checkout.cinetpay.com/v2/payment";
            $response = Http::post($api_url, [
                'apikey' => $api_key,
                'site_id' => $site_id,
                'transaction_id' => $transaction_id,
                'amount' => intval($car->client_price),
                'currency' => "XOF",
                'notify_url' => "http://127.0.0.1:8000/cinet-notify",
                'return_url' => "http://127.0.0.1:8000",
                'channels' => "ALL",
                'description' => "RESERVATION VEHICULE de location",
            ]);
            if ($response->successful()) {
                // La requête a réussi (code de réponse HTTP 2xx)
                $responseData = $response->json();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'reserve_id' => $CurentTenant->id,
                    'transaction_id' => $transaction_id,
                    'data'    =>$responseData,
                ]);

                // Faire quelque chose avec les données de la réponse
            } else {
                // La requête a échoué
                $statusCode = $response->status();
                return response()->json([
                    'success' => false,
                    'code'    =>$statusCode,
                ]);
                // Traiter l'erreur en conséquence
            }
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
    public function show($id)
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


    public function verifypayement(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'transaction_id' => 'required',
        ]);
        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        DB::beginTransaction();

        try {
            $http_request = Http::post('https://api-checkout.cinetpay.com/v2/payment/check',[
                'apikey' => env('CINETPAY_API_KEY'),
                'site_id' => env('CINETPAY_SITE_ID'),
                'transaction_id' => $request->transaction_id
            ]);

            $response = $http_request->json();
            if($response['code']==00){
                $payements = Payement::where('code_payement', $request->transaction_id)->first();
                $payement = Payement::find($payements->id);
                $payement->state = true;
                $payement->update();
                CurentTenant::where('payement_id', $payement->id)->update([ 'state' => true]);
                DB::commit();
                return response()->json([
                    'payement_status' => true
                ]);
            }
    
            return response()->json([
                'payement_status' => false
            ]);
           

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur s\'est produite. Veuillez réessayer ou contacter l\'assistance.',
            ], 500);
        }}
}
