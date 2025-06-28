<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    protected $guarded = [];

    /**
     * Get the sales contact for the supplier.
     */
    public function salesContact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_contact_id');
    }

    /**
     * Get the logistics contact for the supplier.
     */
    public function logisticsContact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logistics_contact_id');
    }
}
