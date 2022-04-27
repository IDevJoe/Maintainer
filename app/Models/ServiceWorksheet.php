<?php

namespace App\Models;

use App\Traits\UsesUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorksheet extends Model
{
    use HasFactory, UsesUuids;
    public const INFO_FORM = [
        [
            'type' => [
                'type' => 'select',
                'label' => 'Type',
                'options' => [
                    'Routine' => 'Routine',
                    'Emergency' => 'Emergency'
                ],
            ],
            'updated_mileage' => [
                'type' => 'number',
                'label' => 'New Mileage',
            ],
            'work_performed_by' => [
                'type' => 'text',
                'label' => 'Work Performed By'
            ]
        ],
        [
            'work_description' => [
                'type' => 'textarea',
                'label' => 'Work Description'
            ]
        ]
    ];

    protected $fillable = ['id', 'vehicle_id', 'type', 'work_performed_by', 'work_description', 'updated_mileage', 'closed_at'];

    public function vehicle() {
        return $this->belongsTo('\App\Models\Vehicle');
    }

    public function vehicleServices() {
        return $this->belongsToMany('\App\Models\VehicleService', 'vehicle_service_service_worksheet');
    }
}
