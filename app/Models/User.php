<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DB;
use App\Models\Category;
use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role !== 'admin' && !app()->runningInConsole()) {
                try {
                    $admins = self::where('role', 'admin')->where('notify_new_user', true)->get();
                    if ($admins->count() > 0) {
                        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewUserRegisterNotification($user));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send register notification: ' . $e->getMessage());
                }
            }
        });
    }






       public function questions()
    {
        return $this->hasMany(Question::class, 'user_id');
    }

         public function categories()
    {
        return $this->hasMany(Category::class, 'user_id');
    }

        public function gameTypes()
    {
        return $this->hasMany(GameType::class, 'user_id');
    }


         public function mainCategories()
    {
        return $this->hasMany(MainCategory::class, 'user_id');
    }

    /**
     * Get the animation library entries for the user.
     */
    public function animationUserLibraries()
    {
        return $this->hasMany(AnimationUserLibrary::class, 'user_id');
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'user_id_created');
    }



    public static function getpermissionGroups(){

        $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;
    } //

       public static function getpermissionByGroupName($group_name){
        $permissions = DB::table('permissions')
                        ->select('name','id')
                        ->where('group_name',$group_name)
                        ->get();
        return $permissions;
    }// End Method
}
