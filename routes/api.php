<?php

use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameHelperController;
use App\Http\Controllers\GameOfflinePriceController;
use App\Http\Controllers\GameTypeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\OnlineGameController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\RankingNewController;
use App\Http\Controllers\ProverbController;
use App\Http\Controllers\AnimationFeedbackController;
use App\Http\Controllers\AnimationUserLibraryController;
use App\Http\Controllers\UserCoinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponCompanyController;
use App\Http\Controllers\AvatarCategoryController;
use App\Http\Controllers\AvatarItemController;
use App\Http\Controllers\FreePlanController;
use App\Http\Controllers\OfflineGameCoinsController;
use App\Http\Controllers\GamePurchaseController;
use App\Http\Controllers\GameCouponController;
use App\Http\Controllers\ProblemReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/auth/social-login', [SocialLoginController::class, 'login']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Route::post('/login',[UserController::class,'loginApi']);


// Route::post('/register',[UserController::class,'registerApi']);

    Route::post('/login/email',[UserController::class,'loginApi']);


Route::post('/register/email',[UserController::class,'registerApi']);

Route::post('/validate/register',[UserController::class,'validateRegisterApi']);




Route::post('/game/type',[GameTypeController::class,'getGameTypeApi']);



Route::post('/main/category',[MainCategoryController::class,'getMainCategoryApi']);




Route::post('/categories',[CategoryController::class,'getCategoryApi']);

Route::post('/avatar/categories', [AvatarCategoryController::class, 'getAvatarCategoriesApi']);

Route::post('/avatar/items', [AvatarItemController::class, 'getAvatarItemsByCategoryApi']);
Route::post('/avatar/buy', [AvatarItemController::class, 'buyAvatarItemApi']);


Route::get('/game/helper',[GameHelperController::class,'getGameHelperApi']);



Route::get('/question/{id}',[QuestionController::class,'getQuestionApi']);

// Route::get('/categories',[CategoryController::class,'getCategoryApi'])->middleware('auth:sanctum');


Route::get('/question/{id}',[QuestionController::class,'getQuestionApi']);
Route::post('/game/session/questions',[QuestionController::class,'createGameSessionQuestions']);




//old get Answer
// Route::get('/answer/{id}',[QuestionController::class,'getQuestionAnswerApi']);
// new get ansewer
Route::post('/answer',[QuestionController::class,'getQuestionAnswerApi']);
Route::post('/answer/online',[QuestionController::class,'getQuestionAnswerOnlineApi']);

// Route::get('/answer/online/{id}',[QuestionController::class,'getQuestionAnswerOnlineApi']);

// After getting api key

// Route::post('/create-post', [PostController::class, 'storeNewPostApi'])->middleware('auth:sanctum');


// Route::post('/upload-image/{user_id}', [UserController::class, 'uploadImageApi']);

Route::post('/upload-image', [UserController::class, 'uploadImageApi']);

Route::post('/upload-image/{id}',[UserController::class,'uploadUpadteImageApi']);



Route::post('/edit/user',[UserController::class,'editUserApi']);

Route::get('/all/free/plan',[FreePlanController::class,'getFreePlansApi']);
Route::get('/all/offline/game/coins',[OfflineGameCoinsController::class,'getOfflineGameCoinsApi']);

Route::get('/all/game/purchase',[GamePurchaseController::class,'getGamePurchasesApi']);
Route::post('/apply/game/coupon', [GameCouponController::class, 'applyGameCouponApi']);

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
Route::post('/coupon-companies', [CouponCompanyController::class, 'getCouponCompaniesApi']);
Route::post('/buy-coupon', [CouponCompanyController::class, 'buyCouponApi']);
Route::post('/use-coupon', [CouponCompanyController::class, 'useCouponApi']);


Route::get('/get/sponsor',[SponsorController::class,'getSponsor']);


Route::post('/get/levels',[LevelController::class,'getLevelByPoints']);
Route::post('user/coins-summary', [UserCoinController::class, 'getUserCoinsSummary']);

Route::post('user/coins-details', [UserCoinController::class, 'getUserCoinDetails']);


Route::post('update/user/online-points', [UserController::class, 'updateOnlineUserPoints']);

// Ranking by user wins
Route::post('user/rank', [RankingNewController::class, 'getUserRankApi']);

// Proverbs by rank
Route::post('proverbs/by-rank', [ProverbController::class, 'getProverbsByRankApi']);

// Animations by rank
Route::post('animations/by-rank', [AnimationFeedbackController::class, 'getAnimationsByRankApi']);

// All animations for a user with availability flag
Route::post('user/animations', [AnimationFeedbackController::class, 'getUserAnimationsApi']);

// Animation User Library Routes
Route::post('animation-library', [AnimationUserLibraryController::class, 'index']);
Route::post('animation-library/add', [AnimationUserLibraryController::class, 'store']);
Route::post('animation-library/remove', [AnimationUserLibraryController::class, 'destroy']);


Route::post('update/coins-numbers', [UserCoinController::class, 'updateCoinsNumbers']);








Route::get('get/game-offline-price',[GameOfflinePriceController::class,'getAllGameOfflinePrice']);










Route::post('add/online/game/info',[OnlineGameController::class,'addGameOnlineInfo']);

Route::post('add/online/game/category',[OnlineGameController::class,'addOnlineGameCategory']);

Route::post('add/online/game/users',[OnlineGameController::class,'addOnlineGameUsers']);


Route::post('get/online/game/info',[OnlineGameController::class,'getGameOnlineInfoApi']);
Route::post('get/online/game/category',[OnlineGameController::class,'getCategoryApiByOnlineGameInfoId']);


Route::post('get/question/online',[QuestionController::class,'getQuestionOnlineApi']);

Route::post('get/game/session/question/online',[QuestionController::class,'getGameSessionQuestions']);



Route::post('add/online/game/points',[OnlineGameController::class,'addPoints']);
Route::post('top/online/users/points',[OnlineGameController::class,'topUsersByOnlinePoints']);
Route::post('add/online/game/win', [OnlineGameController::class, 'addOnlineWin']);
Route::post('add/online/game/play-count', [OnlineGameController::class, 'addOnlinePlayCount']);



 Route::post('social/login',[UserController::class,'socialLoginApi']);





// Route::get('/get/levels',[LevelController::class,'getLevel']);

Route::post('/problem-report/create', [ProblemReportController::class, 'storeProblemReportApi']);








