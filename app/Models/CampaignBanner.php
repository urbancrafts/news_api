<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;
class CampaignBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'img_url'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
      }
}
