<?php

namespace App\Imports;

use App\Models\Enrolled;
use Maatwebsite\Excel\Concerns\ToModel;

class EnrolledsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Enrolled([
          'faculty' => $row[1],
          'program' => $row[2],
          'semester' => $row[3],
          'newcomers_poblado' => $row[4],
          'former_students_poblado' => $row[5],
          'total_poblado' => $row[6],
          'newcomers_rionegro' => $row[7],
          'former_students_rionegro' => $row[8],
          'total_rionegro' => $row[9],
          'newcomers_apartado' => $row[10],
          'former_students_apartado' => $row[11],
          'total_apartado' => $row[12],
          'grand_total' => $row[13],
          'status' => $row[14]
        ]);
    }
}
