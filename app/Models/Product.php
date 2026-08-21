<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'details',
        'image',
        'images',
        'size',
        'color',
        'category',
        'price',
        'status',
        'tag_ids',
        'stock',
    ];

    protected $casts = [
        'images' => 'array',
        'tag_ids' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'details',
                'price',
                'category',
                'size',
                'color',
                'status',
                'stock',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Activity log description.
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Product has been {$eventName}";
    }
}