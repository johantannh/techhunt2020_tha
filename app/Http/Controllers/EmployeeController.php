<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CsvImportRequest;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Validator;
use stdClass;

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
}
