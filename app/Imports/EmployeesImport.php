<?php

namespace App\Imports;

use App\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeesImport implements ToCollection, WithStartRow
{
    private $errors = [];
    private $rows = null;

    public function collection(Collection $rows)
    {
        $uncommentedRows = new Collection();
        foreach ($rows as $row) {
            if($row[0][0]!="#"){
                $uncommentedRows[] = $row; 
            }
        }

        $this->rows = $uncommentedRows;
        //$this->rows = $rows;
        $rows = $uncommentedRows;

        $validator = Validator::make($rows->toArray(), [
            '*.0' => 'required|distinct|unique:employees,id',
            '*.1' => 'required|distinct|unique:employees,login',
            '*.2' => 'required',
            '*.3' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $this->errors[] = $error;
                }
            }
        } else if(count($rows) == 0){
            $this->errors[] = "Empty file"
        } else{
            foreach ($rows as $row) 
            {
                Employee::updateOrCreate([
                    'id'       => $row[0],
                    'login'    => $row[1], 
                    'name'     => $row[2],
                    'salary'   => $row[3],
                ]);
            }
        }
    }

    // this function returns all validation errors after import:
    public function getErrors()
    {
        return $this->errors;
    }

    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
