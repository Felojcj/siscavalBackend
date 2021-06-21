<?php

namespace App\Imports;

use App\Graduate;
use Maatwebsite\Excel\Concerns\ToModel;

class GraduatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Graduate([
            'faculty' => $row[1],
            'program' => $row[2],
            'semester' => $row[3],
            'total' => $row[4],
            'status' => $row[5]
        ]);
    }
}
