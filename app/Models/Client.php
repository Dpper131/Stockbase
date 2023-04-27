<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'fullname',
        'name',
        'middlename'
    ];

    public static function localName($uuid){
        $user = Client::where(['id' => $uuid])->first();
        return "(".$user->fullname." ".$user->name.")";
    }
}
