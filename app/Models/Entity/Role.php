<?php

namespace App\Models\Entity;

use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Support\Str;

class Role extends BaseRole
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->keyType = 'string';
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    /**
     * The attributes that should be primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];
}
