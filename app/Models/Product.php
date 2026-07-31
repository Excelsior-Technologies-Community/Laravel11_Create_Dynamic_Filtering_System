<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'details', 'price', 'category', 'size', 'color', 'status', 'stock'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Product has been {$eventName}";
    }
}
