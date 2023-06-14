<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['created_date', 'license_expire_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function getCreatedDateAttribute()
    {
        return date_diff_for_humans($this->created_at);
    }
    
    public function getLicenseExpireAtAttribute()
    {
        return format_date($this->license_expire_date);
    }
}
