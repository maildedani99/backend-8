<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_id',
        'ds_order',
        'total',
    ];

    public static function generateDsOrder()
{
    // Fijos "01" para los primeros dos dígitos
    $prefix = "01";

    // Obtiene los últimos 2 dígitos del año actual
    $year = date('y'); // 'y' devuelve los últimos dos dígitos del año

    // Encuentra el último ds_order generado este año que comience con "01" y el año
    $pattern = "{$prefix}{$year}%";
    $lastOrder = self::where('ds_order', 'LIKE', $pattern)->latest('ds_order')->first();

    if ($lastOrder) {
        // Extrae el número secuencial del último ds_order y lo incrementa
        $lastIncrement = (int) substr($lastOrder->ds_order, 4);
        $newIncrement = $lastIncrement + 1;
    } else {
        // Si no hay órdenes este año con ese patrón, empieza la secuencia
        $newIncrement = 1;
    }

    // Combina el prefijo, el año y el nuevo número secuencial
    // Asegurando que el número secuencial tenga un relleno para completar 4 dígitos
    $ds_order = $prefix . $year . str_pad($newIncrement, 4, '0', STR_PAD_LEFT);
    Log::info('ds_order', ['ds_order'=>$ds_order]);
    return $ds_order;
}


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
