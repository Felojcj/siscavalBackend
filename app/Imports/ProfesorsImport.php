<?php

namespace App\Imports;

use App\Models\Profesor;
use Maatwebsite\Excel\Concerns\ToModel;

class ProfesorsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Profesor([
            'semester' => $row[1],
            'campus' => $row[2],
            'faculty' => $row[3],
            'formation_level' => $row[4],
            'dedication' => $row[5],
            'total' => $row[6],
            'status' => $row[7]
        ]);
    }
}
