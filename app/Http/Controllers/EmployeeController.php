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
use Illuminate\Validation\Rule;

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

        try{
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
        } catch (\Exception $e){
            return response()->json([
                'message' => "error",
            ], 400);
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

    public function getDashboardData(Request $request)
    {   
        $request->offset = $request->start;
        $sortIndex = "";
        switch($request->order[0]["column"]){
            case 0: $sortIndex = "id"; break;
            case 1: $sortIndex = "login"; break;
            case 2: $sortIndex = "name"; break;
            case 3: $sortIndex = "salary"; break;
        }
        $sortDir = ($request->order[0]["dir"] == "asc") ? "+" : (($request->order[0]["dir"] == "desc") ? "-" : "");
        $request->sort = $sortDir."".$sortIndex;

        $response = $this->getEmployeesData($request);
        
        $employees = Employee::count();
        
        $response_arr['data'] = $response->getData();
        $response_arr['draw'] = $request->draw;
        $response_arr['recordsTotal'] = $employees;
        $response_arr['recordsFiltered'] = $employees;

        return response()->json($response_arr, 200);
    }

    public function createEmployee(Request $request){
        // untested at the moment, Create frontend then verify it
        $validator = Validator::make($request->all(), [
            'id' => 'required|distinct|unique:employees,id',
            'login' => 'required|distinct|unique:employees,login',
            'name' => 'required',
            'salary' => 'required|numeric|min:0'
        ]);

        $errors = [];
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $errors[] = $error;
                }
            }
            //return back()->with('error', implode(' ',$errors));
            return response('',400);
            //return response()->json($errors, 400);
        }
        
        $employee = new Employee;
        $employee->id = $request->id;
        $employee->login = $request->login;
        $employee->name = $request->name;
        $employee->salary = $request->salary;
        $employee->save();

        return response('', 200);
    }

    public function getEmployee(Request $request){
        $urlEmpId = $request->id;
        $employee = Employee::find($urlEmpId);
        if(!$employee){
            return response('',400);
        }
        return response()->json($employee, 200);
    }

    public function updateEmployee(Request $request){
        $urlEmpId = $request->id;
        $employee = Employee::find($urlEmpId);

        if(!$employee){
            //return back()->with('error', "Invalid employee ID");
            return response('',400);
        }
        
        $validator = Validator::make($request->all(), [
            'edit-id' => 'required|unique:employees,id,'.$employee->id,
            'login' => [
                'required',
                Rule::unique('employees', 'login')->ignore($employee->login, 'login')
            ],
            'name' => 'required',
            'salary' => 'required|numeric|min:0',
        ]);

        $errors = [];
        if ($validator->fails()) {
            
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $errors[] = $error;
                }
            }
            //return back()->with('error', implode(' ',$errors));
            //return response('',400);
            return response()->json($errors, 400);
        }

        $employee->id = $request['edit-id'];
        $employee->login = $request->login;
        $employee->name = $request->name;
        $employee->salary = $request->salary;
        $employee->save();
        
        return response('', 200);
        //return back()->with('success','Employee \''.$id.'\' updated successfully');
    }

    public function deleteEmployee(Request $request){
        $urlEmpId = $request->id;
        $employee = Employee::find($urlEmpId);
        if(!$employee){
            return response('',400);
        }
        $employee->delete();
        return response('', 200);
    }
}
