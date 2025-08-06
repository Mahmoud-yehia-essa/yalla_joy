<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PayMentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameTypeController;
use App\Http\Controllers\LandPageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\QuestionAIController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('dashboard');
});

// Route::get('/dashboard', function () {
//     return view('admin.index');
// })->middleware(['auth', 'verified','checkUserRole'])->name('dashboard');


Route::controller(DashboardController::class)->middleware(['auth', 'verified','checkUserRole'])->group(function () {


    Route::get('/dashboard', 'showDashboard')->name('dashboard');


});

Route::controller(NotificationController::class)->middleware(['checkUserRole','auth'])->group(function () {


    Route::get('/add/notification', 'sendNotification')->name('send.notification');
    Route::get('/all/notification', 'alldNotification')->name('all.notification');


});

Route::controller(AppVersionController::class)->middleware(['checkUserRole','auth'])->group(function () {


    Route::get('/add/versions', 'addVersions')->name('add.versions');
    Route::post('/update/versions', 'updateVersions')->name('update.versions.store');


});







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');




});


Route::controller(AdminController::class)
->middleware(['checkUserRole','auth'])
->group(function () {
    // Route::get('/admin/logout', 'destroy')->name('admin.logout');

    Route::get('/admin/profile', 'adminProfile')->name('admin.profile');

    Route::post('/admin/profile', 'adminProfileStore')->name('admin.profile.store');

    Route::get('/admin/change/password', 'AdminChangePassword')->name('admin.change.password');


    Route::post('/admin/update/password', 'AdminUpdatePassword')->name('update.password');







});





Route::controller(GameTypeController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/game/type', 'gameType')->name('all.game.type');

    Route::get('/admin/add/game/type', 'addGameType')->name('add.game.type');


    Route::post('/add/game/type' , 'storeGameType')->name('add.game.type.store');

    Route::get('/admin/edit/game/type/{id}', 'editGameType')->name('edit.game.type');

    Route::post('/edit/game/type' , 'editGameTypeStore')->name('edit.game.type.store');


    Route::get('/delete/game/type/{id}' , 'deleteGameType')->name('delete.game.type');

    Route::get('/game/type/inactive/{id}', 'gameTypeInactive')->name('inactive.game.type');


    Route::get('/game/type/active/{id}', 'gameTypeActive')->name('active.game.type');



});



Route::controller(CategoryController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/category', 'category')->name('all.category');

    Route::get('/admin/add/category', 'addCategory')->name('add.category');


    Route::post('/add/category' , 'storeCategory')->name('add.category.store');

    Route::get('/admin/edit/category/{id}', 'editCategort')->name('edit.category');

    Route::post('/edit/category' , 'editCategortStore')->name('edit.category.store');


    Route::get('/delete/category/{id}' , 'deleteCategory')->name('delete.category');

    Route::get('/category/inactive/{id}', 'categoryInactive')->name('inactive.category');


    Route::get('/category/active/{id}', 'categoryActive')->name('active.category');



});

Route::controller(UserController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/users/all', 'getAllUsers')->name('all.users');

    Route::get('/user/add', 'addUser')->name('add.user');

    Route::post('/user/add', 'addUserStore')->name('add.user.store');




    Route::get('/user/edit/{id}', 'editUser')->name('edit.user');

    Route::post('/user/edit', 'editUserStore')->name('edit.user.store');



    Route::get('/user/inactive/{id}', 'userInactive')->name('inactive.user');


    Route::get('/user/active/{id}', 'userActive')->name('active.user');


    Route::get('/user/delete/{id}', 'deleteUser')->name('delete.user');









});







Route::controller(GameController::class)->middleware(['checkUserRole','auth'])->group(function () {

    Route::get('/games/all', 'allGames')->name('all.games');

    Route::get('/games/details/{id}', 'detailsGames')->name('details.games');
    Route::get('/games/delete/{id}', 'deleteGame')->name('delete.games');


});




Route::controller(AdsController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/add/ads' , 'addAds')->name('add.ads');

    // addAds
});

 // Report All Route
 Route::controller(ReportController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/report/view' , 'ReportView')->name('report.view');


    Route::post('/search/by/date' , 'SearchByDate')->name('search-by-date');

    Route::post('/search/by/month' , 'SearchByMonth')->name('search-by-month');
    Route::post('/search/by/year' , 'SearchByYear')->name('search-by-year');

    Route::get('/order/by/user' , 'OrderByUser')->name('order.by.user');
    Route::post('/search/by/user' , 'SearchByUser')->name('search-by-user');


});

Route::controller(QuestionController::class)->middleware(['checkUserRole','auth'])->group(function () {

    Route::get('/admin/add/question', 'addQuestion')->name('add.question');
    Route::post('/admin/add/question', 'addQuestionStore')->name('add.question.store');


    Route::get('/admin/all/question', 'allQuestion')->name('all.question');


    Route::get('/admin/edit/question/{id}', 'editQuestion')->name('edit.question');


    Route::post('/admin/edit/question', 'editQuestionStore')->name('edit.question.store');


    Route::get('/question/delete/{id}', 'deleteQuestion')->name('delete.question');









});


// Coupon controller

 Route::controller(CouponController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///

         Route::get('/all/coupon', 'AllCoupon')->name('all.coupon');
        Route::get('/add/coupon', 'AddCoupon')->name('add.coupon');
        Route::post('/store/coupon', 'StoreCoupon')->name('store.coupon');

        Route::get('/edit/coupon/{id}', 'EditCoupon')->name('edit.coupon');
        Route::post('/update/coupon', 'UpdateCoupon')->name('update.coupon');
        Route::get('/delete/coupon/{id}', 'DeleteCoupon')->name('delete.coupon');


});


/// price

 Route::controller(PriceController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///

         Route::get('/all/price', 'allPrice')->name('all.price');
        Route::get('/add/price', 'addPrice')->name('add.price');
        Route::post('/add/price', 'addPriceStore')->name('add.price.store');

                Route::get('/delete/price/{id}', 'deletePrice')->name('delete.price');
        Route::get('/edit/price/{id}', 'editPrice')->name('edit.price');



        Route::post('/edit/price', 'editPriceStore')->name('edit.price.store');


});



Route::middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


 Route::controller(SponsorController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/add/sponsor/home/cate' , 'addSponsorHomeCate')->name('sponsor.add.cate');
        Route::get('/add/sponsor/question' , 'addSponsorQuestion')->name('sponsor.add.question');


        Route::post('/edit/sponsor/home/cate', 'editHomeCateStore')->name('edit.home.cate.store');

    // Route::post('/search/by/date' , 'SearchByDate')->name('search-by-date');

    // Route::post('/search/by/month' , 'SearchByMonth')->name('search-by-month');
    // Route::post('/search/by/year' , 'SearchByYear')->name('search-by-year');

    // Route::get('/order/by/user' , 'OrderByUser')->name('order.by.user');
    // Route::post('/search/by/user' , 'SearchByUser')->name('search-by-user');


});




Route::controller(QuestionAIController::class)->middleware(['checkUserRole','auth'])->group(function () {

    Route::get('/admin/add/question/ai', 'addQuestionAi')->name('add.question.ai');
    Route::post('/admin/add/question/to/game/ai', 'addQuestionToGameAi')->name('add.question.to.game.ai');


    Route::post('/admin/get/question/ai', 'getdQuestionStoreAi')->name('get.question.store.ai');


    Route::get('/admin/all/question/ai', 'allQuestionAi')->name('all.question.ai');


    Route::get('/admin/edit/question/ai/{id}', 'editQuestionAi')->name('edit.question.ai');


    Route::post('/admin/edit/question/ai', 'editQuestionStoreAi')->name('edit.question.store.ai');


    Route::get('/question/delete/ai/{id}', 'deleteQuestionAi')->name('delete.question.ai');









});


Route::get('/payment', [PayMentController::class, 'showPaymentPage']);


Route::get('/soon', [LandPageController::class, 'comingSoon']);

require __DIR__.'/auth.php';
