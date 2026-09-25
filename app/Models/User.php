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

    protected $attributes = [
        'number_of_games' => 0,
        'is_game_free' => 'paid',
        'offline_points' => 0,
    ];


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

    /**
     * Get user rank and level information based on online_game_wins.
     *
     * @param \Illuminate\Support\Collection|null $rankings Pre-fetched rankings to prevent N+1 queries.
     * @return array
     */
    public function getRankAndLevel($rankings = null)
    {
        if (!$rankings) {
            $rankings = \App\Models\RankingNew::where('rank_order', '>', 0)
                ->orderBy('rank_order', 'asc')
                ->get();
        } else {
            if ($rankings instanceof \Illuminate\Support\Collection) {
                $rankings = $rankings->where('rank_order', '>', 0)->sortBy('rank_order')->values();
            }
        }

        if ($rankings->isEmpty()) {
            return [
                'rank' => null,
                'rank_name' => 'لا توجد رتب',
                'rank_name_en' => 'No Ranks',
                'level' => 1,
                'levels_count' => 0,
                'wins' => (int) ($this->online_game_wins ?? 0),
                'wins_in_current_level' => 0,
                'wins_to_next_level' => 0,
                'display' => 'لا توجد رتب',
            ];
        }

        $wins = (int) ($this->online_game_wins ?? 0);

        $currentRank = null;
        $previousRankTotalWins = 0;

        foreach ($rankings as $rank) {
            if ($wins < $rank->total_wins_to_next_rank) {
                $currentRank = $rank;
                break;
            }
            $previousRankTotalWins = $rank->total_wins_to_next_rank;
        }

        if (!$currentRank) {
            $currentRank = $rankings->last();
            if ($rankings->count() > 1) {
                $previousRankTotalWins = $rankings[$rankings->count() - 2]->total_wins_to_next_rank;
            } else {
                $previousRankTotalWins = 0;
            }
        }

        $userWinsInRank = max(0, $wins - $previousRankTotalWins);
        $winsPerLevel = (int) $currentRank->wins_to_next_level;

        $currentLevel = 1;
        $winsInCurrentLevel = 0;
        $winsToNextLevelInCurrentLevel = 0;

        if ($winsPerLevel > 0) {
            $currentLevel = (int) floor($userWinsInRank / $winsPerLevel) + 1;

            if ($currentRank->levels_count > 0 && $currentLevel > $currentRank->levels_count) {
                $currentLevel = (int) $currentRank->levels_count;
                $winsInCurrentLevel = $winsPerLevel;
                $winsToNextLevelInCurrentLevel = 0;
            } else {
                $winsInCurrentLevel = $userWinsInRank % $winsPerLevel;
                $winsToNextLevelInCurrentLevel = $winsPerLevel - $winsInCurrentLevel;
            }
        }

        return [
            'rank' => $currentRank,
            'rank_name' => $currentRank->rank_name,
            'rank_name_en' => $currentRank->rank_name_en,
            'level' => $currentLevel,
            'levels_count' => (int) $currentRank->levels_count,
            'wins' => $wins,
            'wins_in_current_level' => $winsInCurrentLevel,
            'wins_to_next_level' => $winsToNextLevelInCurrentLevel,
            'display' => $currentRank->rank_name . ' (المستوى ' . $currentLevel . ' من ' . $currentRank->levels_count . ')',
        ];
    }
}
