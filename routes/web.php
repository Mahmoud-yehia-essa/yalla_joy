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
use App\Http\Controllers\GameCoinController;
use App\Http\Controllers\GameTypeController;
use App\Http\Controllers\LandPageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\QuestionAIController;
use App\Http\Controllers\GameelEmentController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TitlePositionController;
use App\Http\Controllers\RewardsSponsorController;

Route::get('/', function () {
    // return view('welcome');
        return redirect()->route('coming.soon');

    // return redirect()->route('dashboard');
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


Route::controller(MainCategoryController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/main/category', 'mainCategory')->name('all.main.category');

    Route::get('/admin/add/main/category', 'addMainCategory')->name('add.main.category');


    Route::post('/add/main/category' , 'storeMainCategory')->name('add.main.category.store');

    Route::get('/admin/edit/main/category/{id}', 'editMainCategory')->name('edit.main.category');

    Route::post('/edit/main/category' , 'editMainCategoryStore')->name('edit.main.category.store');


    Route::get('/delete/main/category/{id}' , 'deleteMainCategory')->name('delete.main.category');

    Route::get('/main/category/inactive/{id}', 'mainCategoryInactive')->name('inactive.main.category');


    Route::get('/main/category/active/{id}', 'mainCategoryActive')->name('active.main.category');



});





/// Add Main Element

Route::controller(GameelEmentController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/all/game/element', 'allGameElement')->name('all.game.element');

    Route::get('/add/game/element', 'addGameElement')->name('add.game.element');


    Route::post('/add/game/element' , 'storeGameElement')->name('add.game.element.store');

    Route::get('/edit/game/element/{id}', 'editGameElement')->name('edit.game.element');

    Route::post('/edit/game/element' , 'editGameElementStore')->name('edit.game.element.store');


    Route::get('/delete/game/element/{id}' , 'deleteGameElement')->name('delete.game.element');

    Route::get('/game/element/inactive/{id}', 'gameElementInactive')->name('inactive.game.element');


    Route::get('/game/element/active/{id}', 'gameElementActive')->name('active.game.element');



});



/// End main Element



// TitlePositionController



Route::controller(TitlePositionController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/title/position/all', 'titlePosition')->name('all.title.position');

    Route::get('/title/position/add', 'addTitlePosition')->name('add.title.position');


    Route::post('/title/position/add' , 'storeTitlePosition')->name('add.title.position.store');

    Route::get('/title/position/edit/{id}', 'editTitlePosition')->name('edit.title.position');

    Route::post('/title/position/edit' , 'editTitlePositionStore')->name('edit.title.position.store');


    Route::get('/title/position/delete/{id}' , 'deleteTitlePosition')->name('delete.title.position');

    Route::get('/title/position/inactive/{id}', 'titlePositionInactive')->name('inactive.title.position');


    Route::get('/title/position/active/{id}', 'titlePositionActive')->name('active.title.position');






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
    Route::get('/category/active/{id}', 'categoryActive')->name('active.category');


        Route::get('/get-main-categories/{game_type_id}', 'getMainCategories')->name('get.Main.categories');

                Route::get('/get-sub-categories/{main_category_id}', 'getSubCategories')->name('get.sub.categories');


// Route::get('/get-main-categories/{game_type_id}', [App\Http\Controllers\CategoryController::class, 'getMainCategories']);


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


        Route::get('/add/sponsor/new' , 'addSponsorNew')->name('sponsor.add.new');

    Route::get('/edit/sponsor/{id}' , 'editSponsor')->name('edit.sponsor');
        Route::get('/add/sponsor/question' , 'addSponsorQuestion')->name('sponsor.add.question');


        Route::post('/edit/sponsor/home/cate', 'editHomeCateStore')->name('edit.home.cate.store');

        Route::post('/add/sponsor/new', 'addSponsorStore')->name('add.sponsor.new');

        Route::get('/all/sponsor' , 'allSponsor')->name('sponsor.all');

                Route::get('/delete/sponsor/{id}', 'deleteSponsor')->name('delete.sponsor');


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



 Route::controller(RewardsSponsorController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///



         Route::get('/all/rewards/sponsors', 'AllRewardsSponsors')->name('all.rewards.sponsors');
        Route::get('/add/rewards/sponsors', 'AddRewardsSponsors')->name('add.rewards.sponsors');
        Route::post('/store/rewards/sponsors', 'StoreRewardsSponsors')->name('store.rewards.sponsors');

        Route::get('/edit/rewards/sponsors/{id}', 'EditRewardsSponsors')->name('edit.rewards.sponsors');
        Route::post('/update/rewards/sponsors', 'UpdateRewardsSponsors')->name('update.rewards.sponsors');
        Route::get('/delete/rewards/sponsors/{id}', 'DeleteRewardsSponsors')->name('delete.rewards.sponsors');

        Route::get('/get/all/rewards/users', 'getAllRewardsUsers')->name('get.all.rewards.users');
        Route::get('/delete/rewards/users/{id}', 'deleteRewardsUsers')->name('delete.rewards.users');





});


 Route::controller(GameCoinController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///



        Route::get('/all/game/coin', 'AllGameCoin')->name('all.game.coin');
        Route::get('/add/game/coin', 'AddGameCoin')->name('add.game.coin');
        Route::post('/store/game/coin', 'StoreGameCoin')->name('store.game.coin');

        Route::get('/edit/game/coin/{id}', 'EditGameCoin')->name('edit.game.coin');
        Route::post('/update/game/coin', 'UpdateGameCoin')->name('update.game.coin');
        Route::get('/delete/game/coin/{id}', 'DeleteGameCoin')->name('delete.game.coin');






});


Route::get('/payment', [PayMentController::class, 'showPaymentPage']);


Route::get('/soon', [LandPageController::class, 'comingSoon'])->name('coming.soon');;

require __DIR__.'/auth.php';
