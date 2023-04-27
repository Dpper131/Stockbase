<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'vendor_code',
        'fixed_price',
        'type',
        'count',
        'date',
        'client_id'
    ];

    public static function allcountNot()
    {
        return DB::selectOne("select count(id) filter(where status='ожидается') from applications");
    }

    public static function getAppLowInf(string $type)
    {
        $appl_allCodes=null;
        switch ($type) {
            case "yes":
                $appl_allCodes = Application::where(['status' => 'согласовано'])->orderBy('date', 'desc')->get();
                break;
            case "no":
                $appl_allCodes = Application::where(['status' => 'ожидается'])->orderBy('date', 'desc')->get();
                break;
            case "all":
                $appl_allCodes = Application::orderBy('date', 'desc')->get();
                break;
        }
        $resulttoArray = json_decode(json_encode($appl_allCodes), true);
        return $resulttoArray;
    }

    public static function getAppLowInfClient(string $type, $id)
    {
        $appl_allCodes=null;
        switch ($type) {
            case "yes":
                $appl_allCodes = Application::where(['status' => 'согласовано', 'client_id' => $id])->orderBy('date', 'desc')->get();
                break;
            case "no":
                $appl_allCodes = Application::where(['status' => 'ожидается', 'client_id' => $id])->orderBy('date', 'desc')->get();
                break;
            case "all":
                $appl_allCodes = Application::where(['client_id' => $id])->orderBy('date', 'desc')->get();
                break;
        }
        $resulttoArray = json_decode(json_encode($appl_allCodes), true);
        return $resulttoArray;
    }

    public function getAppInfo(): array
    {
        return DB::select("select * from application_info(?)",[$this->id]);
    }
    public function checkAndContrInfo(){
        $result = array();
        $type = $this->type=='Продажа' ? 'Покупка' : 'Продажа';
        $app = Application::where([
            'status' => $this->status,
            'vendor_code' => $this->vendor_code,
            'count' => $this->count,
            'fixed_price' => $this->fixed_price,
            'type' => $type,
        ])->first();
        if($app == null){
            $result[] = array(0 => array("stat" =>"Нет"));
            return $result;
        }
        else{
            $result[] = array(0 => array(
                "stat" =>"Да",
                "buyid" => $type == 'Покупка' ? $app->id : $this->id,
                "sellid" => $type == 'Продажа' ? $app->id : $this->id,
                "valid_code" => $this->vendor_code,
                "count" => $this->count,
                "sum" => $this->count*$this->fixed_price,

            ));
            return $result;
        }
    }
}
