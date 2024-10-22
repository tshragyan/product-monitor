<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringQueue extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_STARTED = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    protected $fillable = [
        'product_id',
        'status',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
