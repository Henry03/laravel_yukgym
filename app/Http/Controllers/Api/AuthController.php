<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\kirimemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\Token;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();    // Mengambil seluruh data input dan menyimpan dalam variabel registratinoData
        //  $validate = Validator::make($registrationData, [
        $validate = $request->validate([
            'username' => 'required|unique:users',
            'notelp' => 'required|min:10|max:13',
            'email' => 'required|email:rfc,dns|unique:users',
            'birthdate' => 'required',
            'password' => 'required',
            'passwordConfirmation' => 'required|same:password',
            'fingerprint' => 'required',
            // 'image' => 'mimes:jpeg,jpg,png,gif,svg|max:2048'
         ]);    // rule validasi input saat register

        //  if($validate->fails())    // Mengecek apakah inputan sudah sesuai dengan rule validasi
        //     return response(['message' => $validate->errors()], 400);   // Mengembalikan error validasi input
        //  else
        //     return response(['message' => "success"]);
            
        $registrationData['password'] = bcrypt($request->password); // Untuk meng-enkripsi password
        // $uploadFolder = 'public';
        // $image = $request->file('image');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        // $registrationData['image'] = $image_uploaded_path;
        $user = User::create($registrationData);    // Membuat user baru
        // event(new Registered($registrationData));
        $user->sendEmailVerificationNotification();
        return response([
            'message' => 'Register Success, check your email to verify',
            'user' => $user
        ], 200); // return data user dalam bentuk json

    }

    public function login (Request $request){
        $loginData = $request->all();
 
        $validate = Validator::make($loginData, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())    // Mengecek apakah inputan sudah sesuai dengan rule validasi
            return response(['message'=> $validate->errors()->first(),'errors' => $validate->errors()], 400);   // Mengembalikan error validasi input

        if(!Auth::attempt($loginData))    // Mengecek apakah inputan sudah sesuai dengan rule validasi
            return response(['message' => 'Invalid Credentials', 'data'=>$loginData], 401);   // Mengembalikan error gagal login

        $user = Auth::user();
        if($user->email_verified_at == null){
            return response(['message' => 'Email Not Verified, check your inbox or spam to verify'], 401);
        }
        $token = $user->createToken('Authentication Token')->accessToken;   //generate token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); // return data user dan token dalam bentuk json

    }

    public function logout(Request $request){
        $user = Auth::user()->token();
        $dataUser = Auth::user();
        $user->revoke();
        return response([
            'message' => 'Logout Succes',
            'user' => $dataUser
        ]);
    }

    public function authCheck(){
        if(auth()->user()){
            return response([
                'message' => 'Authenticated',
                'data' => auth()->user()
            ], 200);
        }else{
            return response(
                [
                    'message' => 'Unauthenticated',
                    'data' => null
                ], 401
            );
        }
    }

    public function email(){
        Mail::to('henryyanggg@gmail.com')->send(new kirimemail());
    }

    public function verify(Request $request){
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return view('emailverified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('emailverify');
    }
}
