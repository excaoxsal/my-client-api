<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class MyClient extends Model
{
    protected $table = 'my_client';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'is_project', 'self_capture',
        'client_prefix', 'client_logo', 'address',
        'phone_number', 'city', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected static function booted()
    {
        static::created(function ($client) {
            Redis::set($client->slug, json_encode($client));
        });

        static::updated(function ($client) {
            Redis::del($client->slug);
            Redis::set($client->slug, json_encode($client));
        });

        static::deleted(function ($client) {
            Redis::del($client->slug);
        });
    }
}
