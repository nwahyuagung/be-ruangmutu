<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityIndicatorProfileDataPeriod extends AppModel
{
    use SoftDeletes;

    protected $table    =   'quality_indicator_profile_data_periods';

    protected $fillable =   [
        'profile_id',
        'name',
    ];
}