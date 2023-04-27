<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    public $timestamps = false;
    protected $primaryKey = 'vendor_code';
    protected $fillable = [
        'vendor_code',
        'type',
        'company_name',
        'count',
        'date'
    ];
    public function getStockLowInf(string $daytime){
        $stocks_allCodes =Stock::orderBy('vendor_code')->get();
        $result= null;
        foreach ($stocks_allCodes as $vendor_code) {
            $inform = DB::selectOne("select * from stock_short_inf(?,?)", [$vendor_code['vendor_code'],$daytime]);
            if (empty($inform)) {
                $inform = (object)[
                    'vendor_code' => $vendor_code['vendor_code'],
                    'type' => $vendor_code['type'],
                    'company_name' => $vendor_code['company_name'],
                    'now_price' => '',
                    'change_perc' => '',
                    'volume' => '',
                    'offers_count' => '',
                ];
            }
            $result[] = $inform;
        }
        $resulttoArray= json_decode(json_encode($result), true);
        return $resulttoArray;

}
 public function getStockChartInfo(): array
 {
    return DB::select("select * from chart_short_inf(?)",[$this->vendor_code]);
 }
    public function getFullStockInfo()
    {
        return DB::selectOne("select * from stock_full_inf(?)",[$this->vendor_code]);
    }
    public function StockName(): string
    {
        $res=$this->type." ".$this->company_name;
        return $res;
    }
}
