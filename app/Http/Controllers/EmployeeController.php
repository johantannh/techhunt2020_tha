<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CsvImportRequest;
use App\Imports\EmployeesImport;
use stdClass;

class EmployeeController extends Controller
{

    public function getUploadPage()
    {
        return view('upload');
    }

    public function uploadEmployees(Request $request)
    {   
        $empImport = new EmployeesImport;
        $import = Excel::import($empImport, request()->file('file'));
        /*
        try {
            $import = Excel::import(new EmployeesImport, request()->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             
             foreach ($failures as $failure) {
                 $failure->row(); // row that went wrong
                 $failure->attribute(); // either heading key (if using heading row concern) or column index
                 $failure->errors(); // Actual error messages from Laravel validator
                 $failure->values(); // The values of the row that has failed.
             }
        }
        */
        if($empImport->getErrors()){
            return response()->json([
                'message' => "fail",
                'rows' => $empImport->getRows(),
                'errors' => $empImport->getErrors()
            ], 400);
                        //->header('Content-Type', 'text/plain')
        } else{
            return response('success', 200)->header('Content-Type', 'text/plain');
        }
        //
        //$result= new stdClass();
        //$result->status = "fail";
        //return json_encode($result);
        //return redirect()->back();
        /*

        if ($request->has('header')) {
            $data = Excel::load($path, function($reader) {})->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path));
        }

        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data = array_slice($data, 0, 2);

            $csv_data_file = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else {
            return redirect()->back();
        }

        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));
        */
    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $contact = new Contact();
            foreach (config('app.db_fields') as $index => $field) {
                if ($data->csv_header) {
                    $contact->$field = $row[$request->fields[$field]];
                } else {
                    $contact->$field = $row[$request->fields[$index]];
                }
            }
            $contact->save();
        }

        return view('import_success');
    }

}
