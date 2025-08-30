<?php

namespace App\Models;

use App\Models\User;
use App\Models\rewardsSponsor;
use Illuminate\Database\Eloquent\Model;

class FollowUserRewards extends Model
{

        protected $guarded = [];

       public function user()
    {
        return $this->belongsTo(User::class, 'user_id_accepted');
    }

         public function rewardsSponsors()
    {
        return $this->belongsTo(rewardsSponsor::class, 'rewards_sponsors_id');
    }
}
