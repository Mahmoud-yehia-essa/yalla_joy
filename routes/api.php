<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameTypeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserCoinController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\GameHelperController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\GameOfflinePriceController;



Route::post('/auth/social-login', [SocialLoginController::class, 'login']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Route::post('/login',[UserController::class,'loginApi']);


// Route::post('/register',[UserController::class,'registerApi']);

    Route::post('/login/email',[UserController::class,'loginApi']);


Route::post('/register/email',[UserController::class,'registerApi']);



Route::get('/game/type',[GameTypeController::class,'getGameTypeApi']);



Route::post('/main/category',[MainCategoryController::class,'getMainCategoryApi']);




Route::post('/categories',[CategoryController::class,'getCategoryApi']);


Route::get('/game/helper',[GameHelperController::class,'getGameHelperApi']);



Route::get('/question/{id}',[QuestionController::class,'getQuestionApi']);

// Route::get('/categories',[CategoryController::class,'getCategoryApi'])->middleware('auth:sanctum');


Route::get('/question/{id}',[QuestionController::class,'getQuestionApi']);




//old get Answer
// Route::get('/answer/{id}',[QuestionController::class,'getQuestionAnswerApi']);
// new get ansewer
Route::post('/answer',[QuestionController::class,'getQuestionAnswerApi']);


// After getting api key

// Route::post('/create-post', [PostController::class, 'storeNewPostApi'])->middleware('auth:sanctum');


// Route::post('/upload-image/{user_id}', [UserController::class, 'uploadImageApi']);

Route::post('/upload-image', [UserController::class, 'uploadImageApi']);

Route::post('/upload-image/{id}',[UserController::class,'uploadUpadteImageApi']);



Route::post('/edit/user',[UserController::class,'editUserApi']);

// Sava Game
Route::post('/save/game',[GameController::class,'saveGameApi']);
Route::post('/save/team',[GameController::class,'saveTeamGameApi']);

Route::post('/save/category',[GameController::class,'saveGameCatesApi']);

Route::post('/save/questions/register',[GameController::class,'saveGameQuetionApi']);
// Route::post('/save/game/question',[GameController::class,'saveGameApi']);



/// get Gammes
Route::get('/games/by/{id}',[GameController::class,'getGamesByUserId']);


/// get user by email

Route::get('/user/by/{email}',[UserController::class,'getUserByEmail']);

Route::post('/edit/user/password',[UserController::class,'editUserPasswordApi']);



Route::post('/update/user/games/number',[UserController::class,'updateUserGamesNumber']);


Route::post('/delete/user',[UserController::class,'deleteUserApi']);


Route::get('/setting/app/{id}',[AppVersionController::class,'getSettingApp']);


Route::get('/get/price',[PriceController::class,'getAllPrice']);


Route::post('/get/coupon',[CouponController::class,'getCouponByNameApi']);


Route::get('/get/sponsor',[SponsorController::class,'getSponsor']);


Route::post('/get/levels',[LevelController::class,'getLevelByPoints']);
Route::post('user/coins-summary', [UserCoinController::class, 'getUserCoinsSummary']);

Route::post('user/coins-details', [UserCoinController::class, 'getUserCoinDetails']);


Route::post('update/user/online-points', [UserController::class, 'updateOnlineUserPoints']);



Route::post('update/coins-numbers', [UserCoinController::class, 'updateCoinsNumbers']);








Route::get('get/game-offline-price',[GameOfflinePriceController::class,'getAllGameOfflinePrice']);





// Route::get('/get/levels',[LevelController::class,'getLevel']);








