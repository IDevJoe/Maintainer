<?php

namespace App\Models;

use App\Traits\UsesUuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleService extends Model
{
    use HasFactory, UsesUuids;

    protected $fillable = ['id', 'vehicle_id', 'frequency_type', 'frequency', 'description', 'last_serviced', 'last_mileage'];

    public const SERVICE_FORM = [
        [
            'description' => [
                'label' => 'Description',
                'type' => 'text',
                'required' => true
            ],
            'frequency_type' => [
                'type' => 'select',
                'options' => [
                    'mi' => 'Miles',
                    'd' => 'Days'
                ],
                'label' => 'Frequency Type',
                'required' => true
            ],
            'frequency' => [
                'type' => 'number',
                'label' => 'Frequency',
                'required' => true
            ]
        ]
    ];

    public function vehicle() {
        return $this->belongsTo('\App\Models\Vehicle');
    }

    public function getNextServiceAttribute() {
        if($this->last_serviced == null)
            return Carbon::now()->toDateString();
        if($this->frequency_type == "mi") {
            return $this->last_mileage + $this->frequency . " miles";
        }
        $cp = Carbon::parse($this->last_serviced);
        return $cp->addDays($this->frequency)->toDateString();
    }
}
