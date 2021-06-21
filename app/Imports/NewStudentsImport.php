<?php

namespace App\Imports;

use App\Models\NewStudent;
use Maatwebsite\Excel\Concerns\ToModel;

class NewStudentsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new NewStudent([
            'faculty' => $row[1],
            'program' => $row[2],
            'semester' => $row[3],
            'enrolled_poblado' => $row[4],
            'admitted_poblado' => $row[5],
            'newcomers_poblado' => $row[6],
            'enrolled_rionegro' => $row[7],
            'admitted_rionegro' => $row[8],
            'newcomers_rionegro' => $row[9],
            'enrolled_apartado' => $row[10],
            'admitted_apartado' => $row[11],
            'newcomers_apartado' => $row[12],
            'status' => $row[13]
        ]);
    }
}
