<?php

namespace App\Http\Controllers;

use App\Models\Dagle;
use Illuminate\Http\Request;
use App\Http\Resources\DagleResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DagleController extends Controller
{
    /**
     * Display a listing of the Dagles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DagleResource::collection(
            Dagle::orderBy('created_at', 'DESC')->get()
        );
    }

    /**
     * Display the specified Dagle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $args = [];
        $args['error'] = false;

        $data = Dagle::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }

        return response()->json(new DagleResource($data), 200);
    }

    /**
     * Store a newly created Dagle in storage.
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
        $data = new Dagle();
        $data->name = $request->name;
        $data->secretariat = $request->secretariat;
        $data->dagle_id = $request->dagle_id;
        $data->save();

        DB::commit();
        return response()->json(new DagleResource($data), 201);
    }

    /**
     * Update the specified Dagle in storage.
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

        $data = Dagle::find($id);
        $all = $request->all();

        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            $data->update($all);
            return response()->json(new DagleResource($data), 200);
        }
    }
    /**
     * Show the form for editing the specified Dagle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ...
    }
    /**
     * Remove the specified Dagle from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $args = array();
        $args['error'] = false;

        $data = Dagle::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            // $data->delete();
            $up = DB::table('Dagles')
                ->where('id', '=', $id)
                ->update(['archive' => 1]);
            return response()->json(new DagleResource($data), 200);
        }
    }

}
