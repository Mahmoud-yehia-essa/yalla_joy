<?php

namespace App\Models;

use App\Models\GameBundleCoin;
use App\Models\GameBundleItem;
use App\Models\GameBundleHelper;
use Illuminate\Database\Eloquent\Model;

class GameBundle extends Model
{
        protected $guarded = [];
         // 🔹 علاقة العملات المرتبطة بالحزمة
    public function bundleCoins()
    {
        return $this->hasMany(GameBundleCoin::class, 'game_bundle_id', 'id');
    }

    // 🔹 علاقة عناصر اللعبة المرتبطة بالحزمة
    public function bundleItems()
    {
        return $this->hasMany(GameBundleItem::class, 'game_bundle_id', 'id');
    }

    // 🔹 علاقة عناصر المساعدة المرتبطة بالحزمة
    public function bundleHelpers()
    {
        return $this->hasMany(GameBundleHelper::class, 'game_bundle_id', 'id');
    }

}
