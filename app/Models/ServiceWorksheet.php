<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorksheet extends Model
{
    use HasFactory;
    /*
     * $table->uuid('vehicle_id');
            $table->string('type');
            $table->string('work_performed_by')->nullable();
            $table->longText('work_description')->nullable();
            $table->integer('updated_mileage')->nullable();
            $table->timestamp('closed_at')->nullable();
     */

    protected $fillable = ['id', 'vehicle_id', 'type', 'work_performed_by', 'work_description', 'updated_mileage', 'closed_at'];
}
