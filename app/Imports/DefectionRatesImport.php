<?php

namespace App\Imports;

use App\Models\DefectionRate;
use Maatwebsite\Excel\Concerns\ToModel;

class DefectionRatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DefectionRate([
            'faculty' => $row[1],
            'program' => $row[2],
            'semester' => $row[3],
            'enrolled_poblado' => $row[4],
            'academic_retirement_poblado' => $row[5],
            'voluntary_retirement_poblado' => $row[6],
            'enrolled_rionegro' => $row[7],
            'academic_retirement_rionegro' => $row[8],
            'voluntary_retirement_rionegro' => $row[9],
            'enrolled_apartado' => $row[10],
            'academic_retirement_apartado' => $row[11],
            'voluntary_retirement_apartado' => $row[12],
            'enrolled_total' => $row[13],
            'academic_retirement_total' => $row[14],
            'voluntary_retirement_total' => $row[15],
            'defection_rate' => $row[16],
            'status' => $row[17]
        ]);
    }
}
