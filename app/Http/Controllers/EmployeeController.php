<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CsvImportRequest;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{

    public function getUploadPage()
    {
        return view('upload');
    }

    public function uploadEmployees(Request $request)
    {   
        // Check if file extension is correct
        $validator = Validator::make([
            'file'      => $request->file,
            'extension' => strtolower($request->file->getClientOriginalExtension()),
        ],
        [
            'file'          => 'required',
            'extension'      => 'required|in:csv',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => "Invalid file extension",
            ], 400);
        }

        $empImport = new EmployeesImport;
        $import = Excel::import($empImport, request()->file('file'));

        if($empImport->getErrors()){
            return response()->json([
                'message' => "fail",
                //'rows' => $empImport->getRows(),
                //'errors' => $empImport->getErrors()
            ], 400);
        } else{
            return response('success', 200)->header('Content-Type', 'text/plain');
        }
    }

    public function getEmployeesData(Request $request)
    {   
        $minSalary = $request->minSalary;
        $maxSalary = $request->maxSalary;
        $offset = $request->offset;
        $limit = $request->limit;
        $sort = $request->sort;
        $orderBy = "";

        Validator::extend('isAscOrDesc', function($attribute, $value, $parameters)
        {
            return ($value[0] == '+' || $value[0] == '-');
        }, "invalid sign");
        
        Validator::extend('isCorrectKey', function($attribute, $value, $parameters)
        {            
            $value = Str::substr($value, 1);
            // %2B or -
            return ($value == 'id' || 
                    $value == 'name' || 
                    $value == 'login' || 
                    $value == 'salary');
        }, "invalid order key");

        // Check if file extension is correct
        $validator = Validator::make([
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'offset'    => $offset,
            'limit'     => $limit,
            'sort'      => $sort,
        ],
        [
            'minSalary' => 'required|min:0',
            'maxSalary' => 'required|gte:minSalary',
            'offset'    => 'required|integer',
            'limit'     => 'required|integer|size:30',
            'sort'      => 'required|isAscOrDesc|isCorrectKey',
        ]);
        

        if($validator->fails()){
            return response()->json([
                $validator->errors()->messages(),
            ], 400);
        }

        if($sort){
            if($sort[0]=='+'){
                $orderBy = "asc";
            } else if($sort[0]=="-"){
                $orderBy = "desc";
            } 
            $sort = Str::substr($sort, 1);
        }

        //DB::table('roles')
        $employees = Employee::whereBetween('salary', [$minSalary, $maxSalary])
            ->orderBy($sort, $orderBy)
            ->limit($limit)
            ->offset($offset)
            ->get(['id', 'name', 'login', 'salary'])
            ->toArray();
        
        return response()->json($employees, 200);
    }

    public function getEmployeeDashboard(Request $request){
        return view('dashboard');
    }
}
