<?php


use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CourseResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('all-users',function(){

    return UserResource::collection(User::all());

    // return new UserResource(User::all());
    // return UserResource::collection(User::all());
});

Route::get('all-course',function(){

    return CourseResource::collection(Course::paginate(2));

    // return new UserResource(User::all());
    // return UserResource::collection(User::all());
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
