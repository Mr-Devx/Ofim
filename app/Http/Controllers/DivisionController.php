<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Resources\DivisionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    // ... autres méthodes du contrôleur ...

    /**
     * Store a newly created division in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'label' => 'required',
            'desc' => 'required|unique:categories,desc',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        DB::beginTransaction();
        $data = new Division();
        $data->name = $request->name;
        $data->secretariat = $request->secretariat;
        $data->dagle_id = $request->dagle_id;
        $data->save();

        DB::commit();
        return response()->json(new DivisionResource($data), 201);
    }

    /**
     * Update the specified division in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'label' => 'required',
            'desc' => 'required|unique:categories,desc',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $data = Division::find($id);
        $all = $request->all();

        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            $data->update($all);
            return response()->json(new DivisionResource($data), 200);
        }
    }
    /**
     * Display a listing of the divisions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DivisionResource::collection(
            Division::orderBy('created_at', 'DESC')->get()
        );
    }

    /**
     * Display the specified division.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $args = [];
        $args['error'] = false;

        $data = Division::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }

        return response()->json(new DivisionResource($data), 200);
    }

    /**
     * Remove the specified division from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $args = array();
        $args['error'] = false;

        $data = Division::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            // $data->delete();
            $up = DB::table('divisions')
                ->where('id', '=', $id)
                ->update(['archive' => 1]);
            return response()->json(new DivisionResource($data), 200);
        }
    }

    /**
     * Show the form for editing the specified division.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ...
    }

    // ... autres méthodes du contrôleur ...
}
