<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CampaignBanner;
class Campaign extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'from_date',
        'to_date',
        'currency',
        'daily_budget',
        'total_budget'
    ];

    protected $hidden = [
      'created_at',
      'updated_at'
  ];


  public function banner(){
    return $this->hasMany(CampaignBanner::class);
  }
}
