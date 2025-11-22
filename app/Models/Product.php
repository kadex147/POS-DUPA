<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock after transaction
     */
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Add stock
     */
    public function addStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
        return true;
    }
}