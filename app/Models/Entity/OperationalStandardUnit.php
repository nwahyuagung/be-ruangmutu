<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationalStandardUnit extends AppModel
{
    use SoftDeletes;

    protected $table    =   'operational_standard_units';

    protected $guarded  =   ['id'];
}
