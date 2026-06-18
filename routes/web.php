<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\GameBundleController;
use App\Http\Controllers\GameCoinController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameelEmentController;
use App\Http\Controllers\GameHelperController;
use App\Http\Controllers\GameItemController;
use App\Http\Controllers\GameOfflinePriceController;
use App\Http\Controllers\GameTypeController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\LandPageController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationDashboardController;
use App\Http\Controllers\OnlineGameController;
use App\Http\Controllers\PayMentController;
use App\Http\Controllers\PointWithCointController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionAIController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RewardsSponsorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\TitlePositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGameController;
use App\Http\Controllers\ProverbController;
use App\Http\Controllers\RankingNewController;
use App\Http\Controllers\AnimationFeedbackController;
use App\Http\Controllers\CouponCompanyController;
use App\Http\Controllers\CouponCompanyUserUsedController;
use App\Http\Controllers\AvatarCategoryController;
use App\Http\Controllers\AvatarItemController;
use App\Http\Controllers\FreePlanController;
use App\Http\Controllers\GamePurchaseController;
use App\Http\Controllers\GameCouponController;
use App\Http\Controllers\ProblemReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
        return redirect()->route('coming.soon');

    // return redirect()->route('dashboard');
});

// Public coupon display page (no auth required)
Route::get('/coupon', [CouponCompanyController::class, 'showCouponPage'])->name('coupon.show');


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



Route::get('/all/admin' , 'AllAdmin')->name('all.admin');
Route::get('/add/admin' , 'AddAdmin')->name('add.admin');
Route::post('/admin/add', 'addAdminStore')->name('add.admin.store');


  Route::get('/admin/edit/{id}', 'editAdmin')->name('edit.admin');

    Route::post('/admin/edit', 'editAdminStore')->name('edit.admin.store');





    Route::get('/admin/delete/{id}', 'deleteAdmin')->name('delete.admin');






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


Route::controller(ProverbController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/all/proverb', 'allProverb')->name('all.proverb');
    Route::get('/admin/add/proverb', 'addProverb')->name('add.proverb');
    Route::post('/admin/add/proverb', 'storeProverb')->name('add.proverb.store');
    Route::get('/admin/edit/proverb/{id}', 'editProverb')->name('edit.proverb');
    Route::post('/admin/edit/proverb', 'updateProverb')->name('edit.proverb.store');
    Route::get('/admin/delete/proverb/{id}', 'deleteProverb')->name('delete.proverb');
});

Route::controller(RankingNewController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/all/rankings', 'allRankings')->name('all.rankings.new');
    Route::get('/admin/add/ranking', 'addRanking')->name('add.ranking.new');
    Route::post('/admin/add/ranking', 'storeRanking')->name('add.ranking.new.store');
    Route::get('/admin/edit/ranking/{id}', 'editRanking')->name('edit.ranking.new');
    Route::post('/admin/edit/ranking', 'updateRanking')->name('edit.ranking.new.store');
    Route::get('/admin/delete/ranking/{id}', 'deleteRanking')->name('delete.ranking.new');
});

Route::controller(AnimationFeedbackController::class)->middleware(['checkUserRole','auth'])->group(function () {
    Route::get('/admin/all/animation', 'allAnimation')->name('all.animation');
    Route::get('/admin/add/animation', 'addAnimation')->name('add.animation');
    Route::post('/admin/add/animation', 'storeAnimation')->name('add.animation.store');
    Route::get('/admin/edit/animation/{id}', 'editAnimation')->name('edit.animation');
    Route::post('/admin/edit/animation', 'updateAnimation')->name('edit.animation.store');
    Route::get('/admin/delete/animation/{id}', 'deleteAnimation')->name('delete.animation');
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


        Route::get('/admin/filter/category', 'fillterCategory')->name('filter.category');
    Route::post('/search/filter' , 'fillterCategorySearch')->name('filter.category.search');





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

    Route::get('/user/photo/approve/{id}', 'approveUserPhoto')->name('user.photo.approve');
    Route::get('/user/photo/reject/{id}', 'rejectUserPhoto')->name('user.photo.reject');
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
    Route::post('/question/delete-multiple', 'deleteMultipleQuestions')->name('delete.multiple.questions');


    Route::get('/admin/filter/question', 'fillterQuestion')->name('filter.question');
    Route::post('/search/filter/question' , 'fillterQuestionSearch')->name('filter.question.search');

    Route::get('/admin/verify/question/images', 'verifyQuestionImages')->name('verify.question.images');

















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


// Coupon Company controller

 Route::controller(CouponCompanyController::class)->middleware(['checkUserRole','auth'])->group(function(){

         Route::get('/all/coupon-companies', 'allCouponCompanies')->name('all.coupon_companies');
        Route::get('/add/coupon-companies', 'addCouponCompany')->name('add.coupon_companies');
        Route::post('/store/coupon-companies', 'storeCouponCompany')->name('store.coupon_companies');

        Route::get('/edit/coupon-companies/{id}', 'editCouponCompany')->name('edit.coupon_companies');
        Route::post('/update/coupon-companies', 'updateCouponCompany')->name('update.coupon_companies');
        Route::get('/delete/coupon-companies/{id}', 'deleteCouponCompany')->name('delete.coupon_companies');


});


// Coupon Company Used record

 Route::controller(CouponCompanyUserUsedController::class)->middleware(['checkUserRole','auth'])->group(function(){

         Route::get('/all/used-coupon-companies', 'allUsedCoupons')->name('all.used_coupon_companies');
        Route::get('/delete/used-coupon-companies/{id}', 'deleteUsedCoupon')->name('delete.used_coupon');


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






 Route::controller(GameOfflinePriceController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///

         Route::get('/all/game/offline/price', 'allGameOfflinePrice')->name('all.game.offline.price');
        Route::get('/add//game/offline/price', 'addGameOfflinePrice')->name('add.game.offline.price');
        Route::post('/add//game/offline/price', 'addGameOfflinePriceStore')->name('add.game.offline.price.store');

                Route::get('/delete/game/offline/price/{id}', 'deleteGameOfflinePrice')->name('delete.game.offline.price');
        Route::get('/edit/game/offline/price/{id}', 'editGameOfflinePrice')->name('edit.game.offline.price');



        Route::post('/edit/game/offline/price', 'editGameOfflinePriceStore')->name('edit.game.offline.price.store');


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




// Role



Route::controller(RoleController::class)->middleware(['checkUserRole','auth'])->group(function () {

    Route::get('/all/permission', 'allPermission')->name('all.permission');

 Route::get('/add/permission', 'addPermission')->name('add.permission');


         Route::post('/add/permission', 'addPermissiontore')->name('add.permission.store');


    // Route::post('/admin/add/question/to/game/ai', 'addQuestionToGameAi')->name('add.question.to.game.ai');

 Route::get('/edit/permission/{id}', 'editPermission')->name('edit.permission');



    Route::post('/edit/permission' , 'editPermissionStore')->name('edit.permission.store');


        Route::get('/delete/permission/{id}', 'deletePermission')->name('delete.permission');





        /// Roles


            Route::get('/all/roles', 'allRoles')->name('all.roles');
            Route::get('/add/roles', 'addRoles')->name('add.roles');
             Route::post('/add/roles', 'addRolesStore')->name('add.roles.store');
            Route::get('/edit/roles/{id}', 'editRoles')->name('edit.roles');
    Route::post('/edit/roles' , 'editRoleStore')->name('edit.roles.store');

        Route::get('/delete/roles/{id}', 'deleteRole')->name('delete.roles');





        /// Role in permission

 Route::get('/add/roles/permission' , 'AddRolesPermission')->name('add.roles.permission');
 Route::post('/role/permission/store' , 'RolePermissionStore')->name('role.permission.store');



 Route::get('/edit/role/permission/{id}',  'EditRolePermission')->name('role.permission.edit');
Route::post('/update/role/permission/{id}',  'UpdateRolePermission')->name('role.permission.update');




});




// endRole





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



// Route::controller(GameItemController::class)->middleware(['checkUserRole','auth'])->group(function() {
//     Route::get('/all/game-item', 'allGameItem')->name('all.game.item');
//     Route::get('/add/game-item', 'addGameItem')->name('add.game.item');
//     Route::get('/edit/game-item/{id}', 'editGameItem')->name('edit.game.item');

//     Route::post('/store/game-item', 'storeGameItem')->name('store.game.item');
//     Route::post('/update/game-item/{id}', 'updateGameItem')->name('update.game.item');

//     Route::get('/delete/game-item/{id}', 'deleteGameItem')->name('delete.game.item');
// });



Route::controller(GameItemController::class)
    ->middleware(['checkUserRole', 'auth'])
    ->group(function () {

        Route::get('/all/game-item', 'allGameItem')->name('all.game.item');
        Route::get('/add/game-item', 'addGameItem')->name('add.game.item');
        Route::get('/edit/game-item/{id}', 'editGameItem')->name('edit.game.item');

        Route::post('/store/game-item', 'storeGameItem')->name('store.game.item');
        Route::post('/save/game-item', 'saveGameItem')->name('save.game.item');

        // Route::post('/update/game-item', 'updateGameItem')->name('update.game.item');

        Route::post('/update/game-item/{id}', 'updateGameItem')->name('update.game.item');

        Route::get('/delete/game-item/{id}', 'deleteGameItem')->name('delete.game.item');

        Route::get('/game-item/inactive/{id}', 'gameItemInactive')->name('inactive.game.item');
        Route::get('/game-item/active/{id}', 'gameItemActive')->name('active.game.item');
    });




    Route::controller(PointWithCointController::class)
    ->middleware(['checkUserRole', 'auth'])
    ->group(function () {

        Route::get('/all/point-coin', 'allPointCoin')->name('all.point.coin');
        Route::get('/add/point-coin', 'addPointCoin')->name('add.point.coin');


        // باستخدام PointCoin كمل نفس فكرة ولكن
        Route::get('/edit/point-coin/{id}', 'editPointCoin')->name('edit.point.coin');

        Route::post('/store/point-coin', 'storePointCoin')->name('store.point.coin');
        Route::post('/save/point-coin', 'savePointCoin')->name('save.point.coin');

        // Route::post('/update/game-item', 'updateGameItem')->name('update.game.item');

        Route::post('/update/point-coin/{id}', 'updatePointCoin')->name('update.point.coin');

        Route::get('/delete/point-coin/{id}', 'deletePointCoin')->name('delete.point.coin');

        Route::get('/point-coin/inactive/{id}', 'pointCoinInactive')->name('inactive.point.coin');
        Route::get('/point-coin/active/{id}', 'pointCoinActive')->name('active.point.coin');
    });



//     Route::controller(GameHelperController::class)->middleware(['checkUserRole','auth'])->group(function () {

//     Route::get('/all/game-helper', 'allGameHelper')->name('all.game.helper');
//     Route::get('/add/game-helper', 'addGameHelper')->name('add.game.helper');
//     Route::post('/store/game-helper', 'storeGameHelper')->name('store.game.helper');
//     Route::get('/edit/game-helper/{id}', 'editGameHelper')->name('edit.game.helper');
//     Route::post('/update/game-helper/{id}', 'updateGameHelper')->name('update.game.helper');
//     Route::get('/delete/game-helper/{id}', 'deleteGameHelper')->name('delete.game.helper');

//     Route::get('/game-helper/inactive/{id}', 'gameHelperInactive')->name('inactive.game.helper');
//     Route::get('/game-helper/active/{id}', 'gameHelperActive')->name('active.game.helper');
// });



Route::controller(GameHelperController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/all/game-helper', 'allGameHelper')->name('all.game.helper');
    Route::get('/add/game-helper', 'addGameHelper')->name('add.game.helper');
    Route::get('/edit/game-helper/{id}', 'editGameHelper')->name('edit.game.helper');

    Route::post('/store/game-helper', 'storeGameHelper')->name('store.game.helper');
    Route::post('/update/game-helper', 'updateGameHelper')->name('update.game.helper');

    Route::get('/delete/game-helper/{id}', 'deleteGameHelper')->name('delete.game.helper');

    Route::get('/game-helper/inactive/{id}', 'gameHelperInactive')->name('inactive.game.helper');
    Route::get('/game-helper/active/{id}', 'gameHelperActive')->name('active.game.helper');
});




Route::controller(ExcelController::class)->middleware(['checkUserRole','auth'])->group(function(){

Route::get('/excel', 'index')->name('excel.index');
Route::post('/excel/import',  'import')->name('excel.import');


Route::post('/excel/approved',  'approved')->name('excel.approved');


});



Route::get('/game-bundle/details/{id}', [GameBundleController::class, 'bundleDetails'])->name('game.bundle.details');

Route::controller(GameBundleController::class)->middleware(['checkUserRole','auth'])->group(function(){

    // عرض جميع الحزم
    Route::get('/all/game-bundle', 'allGameBundle')->name('all.game.bundle');

    // إضافة حزمة جديدة
    Route::get('/add/game-bundle', 'addGameBundle')->name('add.game.bundle');

    // تعديل حزمة
    Route::get('/edit/game-bundle/{id}', 'editGameBundle')->name('edit.game.bundle');

    // حفظ حزمة جديدة
    Route::post('/store/game-bundle', 'storeGameBundle')->name('store.game.bundle');

    // تحديث حزمة
    Route::post('/update/game-bundle', 'updateGameBundle')->name('update.game.bundle');

    // حذف حزمة
    Route::get('/delete/game-bundle/{id}', 'deleteGameBundle')->name('delete.game.bundle');

    // تفعيل / إلغاء تفعيل الحزمة
    Route::get('/game-bundle/inactive/{id}', 'gameBundleInactive')->name('inactive.game.bundle');
    Route::get('/game-bundle/active/{id}', 'gameBundleActive')->name('active.game.bundle');

// Route::get('/game-bundle/details/{id}', 'bundleDetails')->name('bundle.details');


});



Route::controller(NotificationDashboardController::class)->middleware(['checkUserRole','auth'])->group(function () {



    Route::get('/notification/read/{id}' , 'setNotificationRead')->name('notification.read');

});


Route::controller(UserGameController::class)
    ->middleware(['checkUserRole','auth'])
    ->group(function () {



// عرض نموذج التعديل
Route::get('/user-game-question/{id}/edit',  'editUserGameQuestion')->name('edit.user.game.question');

// تحديث السؤال
Route::post('/user-game-question/{id}/update', 'updateUserGameQuestion')->name('update.user.game.question');


        // عرض جميع الألعاب
        Route::get('/all/user-games', 'allUserGames')->name('all.user.games');

        // إضافة لعبة جديدة
        Route::get('/add/user-game', 'addUserGame')->name('add.user.game');
        Route::post('/store/user-game', 'storeUserGame')->name('store.user.game');

        // تعديل / تحديث / حذف لعبة
        Route::get('/edit/user-game/{id}', 'editUserGame')->name('edit.user.game');
        Route::post('/update/user-game', 'updateUserGame')->name('update.user.game');
        Route::get('/delete/user-game/{id}', 'deleteUserGame')->name('delete.user.game');

        // حالة اللعبة
        Route::get('/user-game/publish/{id}', 'publishGame')->name('publish.user.game');
        Route::get('/user-game/cancel/{id}', 'cancelGame')->name('cancel.user.game');
        Route::get('/user-game/suspend/{id}', 'suspendGame')->name('suspend.user.game');

        // تفاصيل اللعبة
        Route::get('/user-game/details/{id}', 'userGameDetails')->name('user.game.details');

        // ✅ عرض الأسئلة الخاصة باللعبة
        Route::get('/user-game/{id}/questions', 'userGameQuestions')->name('user.game.questions');

        // ✅ إضافة سؤال
        Route::get('/user-game/{id}/add-question', 'addUserGameQuestion')->name('add.user.game.question');
        Route::post('/user-game/store-question', 'storeUserGameQuestion')->name('store.user.game.question');




        // ✅ حذف سؤال
        Route::get('/user-game/delete-question/{id}', 'deleteUserGameQuestion')->name('delete.user.game.question');
});




Route::controller(ItemTypeController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/all/item-type', 'allItemType')->name('all.item.type');
    Route::get('/add/item-type', 'addItemType')->name('add.item.type');
    Route::get('/edit/item-type/{id}', 'editItemType')->name('edit.item.type');

    Route::post('/store/item-type', 'storeItemType')->name('store.item.type');
    Route::post('/update/item-type', 'updateItemType')->name('update.item.type');

    Route::get('/delete/item-type/{id}', 'deleteItemType')->name('delete.item.type');

    Route::get('/item-type/inactive/{id}', 'itemTypeInactive')->name('inactive.item.type');
    Route::get('/item-type/active/{id}', 'itemTypeActive')->name('active.item.type');
});

Route::controller(RankingController::class)->middleware(['checkUserRole','auth'])->group(function() {

    Route::get('/all/ranking', 'allRanking')->name('all.ranking');
    Route::get('/add/ranking', 'addRanking')->name('add.ranking');
    Route::get('/edit/ranking/{id}', 'editRanking')->name('edit.ranking');

    Route::post('/store/ranking', 'storeRanking')->name('store.ranking');
    Route::post('/update/ranking', 'updateRanking')->name('update.ranking');

    Route::get('/delete/ranking/{id}', 'deleteRanking')->name('delete.ranking');

    // روابط التفعيل والإلغاء
    Route::get('/ranking/inactive/{id}', 'rankingInactive')->name('inactive.ranking');
    Route::get('/ranking/active/{id}', 'rankingActive')->name('active.ranking');



});


Route::controller(LevelController::class)->middleware(['checkUserRole','auth'])->group(function(){

    Route::get('/all/level', 'allLevel')->name('all.level');
    Route::get('/add/level', 'addLevel')->name('add.level');
    Route::get('/edit/level/{id}', 'editLevel')->name('edit.level');

    Route::post('/store/level', 'storeLevel')->name('store.level');
    Route::post('/save/level', 'saveLevel')->name('save.level'); // NEW like GameCoin

    Route::post('/update/level/{id}', 'updateLevel')->name('update.level');
    Route::get('/delete/level/{id}', 'deleteLevel')->name('delete.level');

    Route::get('/level/inactive/{id}', 'levelInactive')->name('inactive.level');
    Route::get('/level/active/{id}', 'levelActive')->name('active.level');
});


 Route::controller(GameCoinController::class)->middleware(['checkUserRole','auth'])->group(function(){




    ///



        Route::get('/all/game/coin', 'allGameCoin')->name('all.game.coin');

                Route::get('/add/game/coin', 'addGameCoin')->name('add.game.coin');

        Route::get('/edit/game/coin/{id}', 'editGameCoin')->name('edit.game.coin');


        Route::post('/store/game/coin', 'StoreGameCoin')->name('store.game.coin');
    Route::post('/save/game/coin', 'SaveGameCoin')->name('save.game.coin'); // NEW

        // Route::get('/edit/game/coin/{id}', 'EditGameCoin')->name('edit.game.coin');
        Route::post('/update/game/coin', 'UpdateGameCoin')->name('update.game.coin');
        Route::get('/delete/game/coin/{id}', 'deleteGameCoin')->name('delete.game.coin');





    Route::get('/game/coin/inactive/{id}', 'gameCoinInactive')->name('inactive.game.coin');


    Route::get('/game/coin/active/{id}', 'gameCoinActive')->name('active.game.coin');



});



 Route::prefix('game-guide')->middleware(['auth','checkUserRole'])->group(function () {
    Route::get('/all', [App\Http\Controllers\GameGuideController::class, 'AllGameGuide'])->name('all.game.guide');
    Route::get('/add', [App\Http\Controllers\GameGuideController::class, 'AddGameGuide'])->name('add.game.guide');
    Route::post('/store', [App\Http\Controllers\GameGuideController::class, 'StoreGameGuide'])->name('store.game.guide');
    Route::get('/edit/{id}', [App\Http\Controllers\GameGuideController::class, 'EditGameGuide'])->name('edit.game.guide');
    Route::post('/update', [App\Http\Controllers\GameGuideController::class, 'UpdateGameGuide'])->name('update.game.guide');
    Route::get('/delete/{id}', [App\Http\Controllers\GameGuideController::class, 'DeleteGameGuide'])->name('delete.game.guide');
});



Route::get('/payment', [PayMentController::class, 'showPaymentPage']);


Route::get('/soon', [LandPageController::class, 'comingSoon'])->name('coming.soon');;



Route::get('/use/joined/by/session/{gameSessionName}', [OnlineGameController::class, 'getOnlineGameInfo'])->name('Online.game.info');;

Route::controller(AvatarCategoryController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/avatar/category', 'allAvatarCategory')->name('all.avatar.category');
    Route::get('/add/avatar/category', 'addAvatarCategory')->name('add.avatar.category');
    Route::post('/store/avatar/category', 'storeAvatarCategory')->name('store.avatar.category');
    Route::get('/edit/avatar/category/{id}', 'editAvatarCategory')->name('edit.avatar.category');
    Route::post('/update/avatar/category', 'updateAvatarCategory')->name('update.avatar.category');
    Route::get('/delete/avatar/category/{id}', 'deleteAvatarCategory')->name('delete.avatar.category');
});

Route::controller(AvatarItemController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/avatar/item', 'allAvatarItem')->name('all.avatar.item');
    Route::get('/add/avatar/item', 'addAvatarItem')->name('add.avatar.item');
    Route::post('/store/avatar/item', 'storeAvatarItem')->name('store.avatar.item');
    Route::get('/edit/avatar/item/{id}', 'editAvatarItem')->name('edit.avatar.item');
    Route::post('/update/avatar/item', 'updateAvatarItem')->name('update.avatar.item');
    Route::get('/delete/avatar/item/{id}', 'deleteAvatarItem')->name('delete.avatar.item');
    Route::get('/avatar/item/{id}/purchased-users', 'purchasedUsers')->name('avatar.item.purchased.users');
});

Route::controller(FreePlanController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/free/plan', 'allFreePlan')->name('all.free.plan');
    Route::get('/add/free/plan', 'addFreePlan')->name('add.free.plan');
    Route::post('/store/free/plan', 'storeFreePlan')->name('store.free.plan');
    Route::get('/edit/free/plan/{id}', 'editFreePlan')->name('edit.free.plan');
    Route::post('/update/free/plan', 'updateFreePlan')->name('update.free.plan');
    Route::get('/delete/free/plan/{id}', 'deleteFreePlan')->name('delete.free.plan');
});

Route::controller(GamePurchaseController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/game/purchase', 'allGamePurchase')->name('all.game.purchase');
    Route::get('/add/game/purchase', 'addGamePurchase')->name('add.game.purchase');
    Route::post('/store/game/purchase', 'storeGamePurchase')->name('store.game.purchase');
    Route::get('/edit/game/purchase/{id}', 'editGamePurchase')->name('edit.game.purchase');
    Route::post('/update/game/purchase', 'updateGamePurchase')->name('update.game.purchase');
    Route::get('/delete/game/purchase/{id}', 'deleteGamePurchase')->name('delete.game.purchase');
});

Route::controller(GameCouponController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/game/coupon', 'allGameCoupon')->name('all.game.coupon');
    Route::get('/add/game/coupon', 'addGameCoupon')->name('add.game.coupon');
    Route::post('/store/game/coupon', 'storeGameCoupon')->name('store.game.coupon');
    Route::get('/edit/game/coupon/{id}', 'editGameCoupon')->name('edit.game.coupon');
    Route::post('/update/game/coupon', 'updateGameCoupon')->name('update.game.coupon');
    Route::get('/delete/game/coupon/{id}', 'deleteGameCoupon')->name('delete.game.coupon');
    Route::get('/game/coupon/active/{id}', 'gameCouponActive')->name('active.game.coupon');
    Route::get('/game/coupon/inactive/{id}', 'gameCouponInactive')->name('inactive.game.coupon');
});

Route::controller(ProblemReportController::class)->middleware(['checkUserRole','auth'])->group(function(){
    Route::get('/all/problem-reports', 'allProblemReports')->name('all.problem.reports');
    Route::post('/problem-report/update-status/{id}', 'updateStatus')->name('update.problem.report.status');
    Route::get('/problem-report/delete/{id}', 'deleteProblemReport')->name('delete.problem.report');
});

require __DIR__.'/auth.php';

