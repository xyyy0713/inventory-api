<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'stock', 'category_id'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    // Scope: filter low stock
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stock', '<=', $threshold);
    }

    // Accessor
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    // Mutator
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
