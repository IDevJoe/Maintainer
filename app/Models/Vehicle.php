<?php

namespace App\Models;

use App\Traits\UsesUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, UsesUuids, SoftDeletes;

    public const VEHICLE_FORM = [
        [
            'year' => [
                'label' => 'Year',
                'type' => 'number',
                'required' => true
            ],
            'make' => [
                'label' => 'Make',
                'type' => 'text',
                'required' => true
            ],
            'model' => [
                'label' => 'Model',
                'type' => 'text',
                'required' => true
            ],
            'color' => [
                'label' => 'Color',
                'type' => 'text',
                'required' => true
            ]
        ],
        [
            'vin' => [
                'label' => 'Vehicle Identification Number',
                'type' => 'text',
                'required' => true
            ],
            'plate' => [
                'label' => 'License Plate Number',
                'type' => 'text',
                'required' => true
            ],
            'miles' => [
                'label' => 'Current Miles',
                'type' => 'number',
                'required' => true
            ]
        ]
    ];

    protected $fillable = [
        'id', 'vin', 'plate', 'year', 'make', 'model', 'color', 'miles', 'user_id'
    ];

    public function services() {
        return $this->hasMany('\App\Models\VehicleService');
    }

    public function user() {
        return $this->belongsTo('\App\Models\User');
    }
}
