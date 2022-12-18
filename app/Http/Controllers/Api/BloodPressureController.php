<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodPressure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BloodPressureController extends Controller
{
    public function index(){
        // get posts
        $bloodPressure = BloodPressure::all();
        
        if(count($bloodPressure) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bloodPressure
            ], 200);
        }// return data semua bloodPressure dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function showbyuser(){
        $user_id = Auth::id();
        // get posts
        $bloodPressure = BloodPressure::where('user_id','=', $user_id)->orderBy('date_time','desc')->get();
        
        if(count($bloodPressure) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bloodPressure
            ], 200);
        }// return data semua bloodPressure dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function showlastdata(){
        $user_id = Auth::id();
        // get posts
        $bloodPressure = BloodPressure::where('user_id','=', $user_id)->orderBy('date_time','desc')->first();
        
        if($bloodPressure != null){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bloodPressure
            ], 200);
        }// return data semua bloodPressure dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function store(Request $request){
        $storeData = $request->all();
        $user_id = Auth::id();
        $storeData['user_id'] = $user_id;
        $validate = Validator::make($storeData, [
            'date_time' => 'required',
            'systolic' => 'required|numeric',
            'diastolic' => 'required|numeric|lt:systolic',
            'user_id' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()->first(),'errors' => $validate->errors()], 400);

        $bloodPressure = BloodPressure::create($storeData); // Membuat sebuah data bloodPressure
        return response([
            'message' => 'Add BloodPressure Success',
            'data' => $bloodPressure
        ], 200);
    }

    public function show($id)   // Method search atau menampilkan sebuah data bloodPressure
    {
        $bloodPressure = BloodPressure::find($id); // Mencari data bloodPressure berdasarkan id
        
        if(!is_null($bloodPressure)){
            return response([
                'message' => 'Retrieve BloodPressure Success',
                'data' => $bloodPressure
            ], 200);
        }
        
        return response([
            'message' => 'BloodPressure Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)   // Method update atau mengubah sebuah data bloodPressure
    {
        $user_id = Auth::id();
        $bloodPressure = BloodPressure::find($id);

        if(is_null($bloodPressure)){
            return response([
                'message' => 'BloodPressure Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $updateData['user_id'] = $user_id;
        $validate = Validator::make($updateData, [
            'date_time' => 'required',
            'systolic' => 'required|numeric',
            'diastolic' => 'required|numeric',
            'user_id' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $bloodPressure->date_time = $updateData['date_time'];
        $bloodPressure->systolic = $updateData['systolic'];
        $bloodPressure->diastolic = $updateData['diastolic'];
        

        if($bloodPressure->save()){
            return response([
                'message' => 'Update BloodPressure Success',
                'data' => $bloodPressure
            ], 200);
        }

        return response([
            'message' => 'Update BloodPressure Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $bloodPressure = BloodPressure::find($id);

        if(is_null($bloodPressure)){
            return response([
                'message' => 'BloodPressure Not Found',
                'data' => null
            ], 404);
        }

        if($bloodPressure->delete()){
            return response([
                'message' => 'Delete BloodPressure Success',
                'data' => $bloodPressure
            ], 200);
        }

        return response([
            'message' => 'Delete BloodPressure Failed',
            'data' => null
        ], 400);
    }
}
