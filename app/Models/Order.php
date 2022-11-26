<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\OutgoingStock;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $model->unique_key = $model->createUniqueKey(Str::random(30));
            // $model->url = 'order-form/'.$model->unique_key;
            // $model->save();

            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));
    
            $model->unique_key = $randomString;
            $model->url = 'order-form/'.$model->unique_key;

        });
    }


    //check if unique_key exists
    // private function createUniqueKey($string){
    //     if (static::whereUniqueKey($unique_key = $string)->exists()) {
    //         $random = rand(1000, 9000);
    //         $unique_key = $string.''.$random;
    //         return $unique_key;
    //     }

    //     return $string;
    // }

    //not used, but alternative to creating unique codes
    public function createOrderCode(Order $order)
    {
        $today = date('Ymd');
        $orderNumbers = Order::where('order_number', 'like', $today.'%')->pluck('order_number');
        do {
            $orderNumber = $today.rand(100000, 999999);
        } while ($orderNumbers->contains($orderNumber));

        $order->order_number = $orderNumber;
    }

    public function hasOrderbump() {
        return (bool) OutgoingStock::where(['order_id'=>$this->id, 'reason_removed'=>'as_orderbump'])->count();
    }

    public function hasUpsell() {
        return (bool) OutgoingStock::where(['order_id'=>$this->id, 'reason_removed'=>'as_upsell'])->count();
    }

    public function outgoingStocks()
    {
        return $this->hasMany(OutgoingStock::class, 'order_id');  
    }

    public function orderLabel() {
        return $this->belongsTo(OrderLabel::class, 'order_label_id');  
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');  
    }

}
