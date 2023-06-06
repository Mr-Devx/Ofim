<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $appends = ['created_date', 'client_price_format', 'km_format'];

    public function scopePublish($query)
    {
        return $query->where('cars.state_id', 3);
    }  
    
    public function getCreatedDateAttribute()
    {
        return date_diff_for_humans($this->created_at);
    }
    
    public function getClientPriceFormatAttribute()
    {
        return format_amount($this->client_price);
    }

    public function getKmFormatAttribute()
    {
        return format_numer($this->km);
    }
}
