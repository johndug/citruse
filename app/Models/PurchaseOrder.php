<?php

namespace App\Models;

use App\Enums\VendorType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'vendor_id',
        'vendor_type',
        'product_code',
        'quantity',
        'delivery_date',
        'total',
        'status',
    ];

    protected $hidden = ['vendor_type', 'id'];

    protected $appends = ['po_id'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getPoIdAttribute(): string
    {
        return $this->vendor_type == VendorType::DISTRIBUTOR->value
                ? 'POD'.Str::padLeft($this->id, 6, '0')
                : 'POS'.Str::padLeft($this->id, 6, '0');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
