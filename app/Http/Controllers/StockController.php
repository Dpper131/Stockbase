<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    function getPage(?Request $request){
        try {
            $resultArray = (new Stock)->getStockLowInf($request->time ?? 'day');
        }
        catch (QueryException){
            return redirect('/stock');
        }
        return view('stocks', ['stocks' => $resultArray]);
    }

    public function show($vendor_code): JsonResponse
    {
        $stock = Stock::where(['vendor_code' => $vendor_code])->first();
        $chart = $stock->getStockChartInfo();
        $stockFullInfo=$stock->getFullStockInfo();
        return response()->json([
            'chart' => $chart,
            'info' => $stockFullInfo,
            'data' => $stock,
        ]);
    }
    public function changeCount(Request $request)
    {
        try {
            Stock::where(['vendor_code' => $request->get('valid_code')])->first()->update(['count' => $request->get('count')]);
        }
        catch (QueryException){
            return redirect()->back()->withErrors(['countNotValid' =>'Количество не соответствует']);
        }
        return back()->with("countStatus", "Количество изменено");

    }
    public function addStock(Request $request){
        $request->validate([
            'company_name' => 'required',
            'type' => 'required',
            'count' => 'required',
        ]);
        if($request['type'] != 'Облигация' && $request['type'] != 'Акция'){
            return redirect()->back()->withErrors(['type' =>'Неверный тип! Доступно только акция или облигация.']);
        }
        $company = Company::where(['name' => $request['company_name']])->first();
        if($company){
            $stock = Stock::where(['company_name' => $company->name,'type' => $request['type']])->exists();
            if($stock){
               return redirect()->back()->withErrors(['type' =>'Такой тип ценных бумаг уже есть.']);
            }
            if(!ctype_digit($request['count']) || $request['count']<=0){
                return redirect()->back()->withErrors(['count' =>'Количество акций должно быть больше нуля и целое.']);
            }
            $stockNew = new Stock;
            $stockNew->company_name= $request['company_name'];
            $stockNew->type= $request['type'];
            $stockNew->count= $request['count'];
            $stockNew->save();
            return back()->with("StockStatus", "Акция добавлена!");
        }
        else return redirect()->back()->withErrors(['company' =>'Такой компании не существует.']);
    }
}
