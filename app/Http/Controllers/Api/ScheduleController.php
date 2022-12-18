<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(){
        // get posts
        $schedule = Schedule::all();
        
        if(count($schedule) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $schedule
            ], 200);
        }// return data semua schedule dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function showbyuser(){
        $user_id = Auth::id();
        // get posts
        $schedule = Schedule::where('user_id','=', $user_id)->orderBy('date','asc')->get();
        
        if(count($schedule) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $schedule
            ], 200);
        }// return data semua schedule dalam bentuk json

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
            'title' => 'required',
            'date' => 'required',
            'activity' => 'required',
            'user_id' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()->first(),'errors' => $validate->errors()], 400);

        $schedule = Schedule::create($storeData); // Membuat sebuah data schedule
        return response([
            'message' => 'Add Schedule Success',
            'data' => $schedule
        ], 200);
    }

    public function show($id)   // Method search atau menampilkan sebuah data schedule
    {
        $schedule = Schedule::find($id); // Mencari data schedule berdasarkan id
        
        if(!is_null($schedule)){
            return response([
                'message' => 'Retrieve Schedule Success',
                'data' => $schedule
            ], 200);
        }
        
        return response([
            'message' => 'Schedule Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)   // Method update atau mengubah sebuah data schedule
    {
        $user_id = Auth::id();
        $schedule = Schedule::find($id);

        if(is_null($schedule)){
            return response([
                'message' => 'Schedule Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $updateData['user_id'] = $user_id;
        $validate = Validator::make($updateData, [
            'title' => 'required',
            'date' => 'required',
            'activity' => 'required',
            'user_id' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $schedule->title = $updateData['title'];
        $schedule->date = $updateData['date'];
        $schedule->activity = $updateData['activity'];
        

        if($schedule->save()){
            return response([
                'message' => 'Update Schedule Success',
                'data' => $schedule
            ], 200);
        }

        return response([
            'message' => 'Update Schedule Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if(is_null($schedule)){
            return response([
                'message' => 'Schedule Not Found',
                'data' => null
            ], 404);
        }

        if($schedule->delete()){
            return response([
                'message' => 'Delete Schedule Success',
                'data' => $schedule
            ], 200);
        }

        return response([
            'message' => 'Delete Schedule Failed',
            'data' => null
        ], 400);
    }
}
