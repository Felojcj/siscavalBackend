<?php

namespace App\Imports;

use App\Models\EnrolledByGender;
use Maatwebsite\Excel\Concerns\ToModel;

class EnrolledByGendersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EnrolledByGender([
            'faculty' => $row[1],
            'program' => $row[2],
            'semester' => $row[3],
            'male_newcomers_poblado' => $row[4],
            'female_newcomers_poblado' => $row[5],
            'male_newcomers_rionegro' => $row[6],
            'female_newcomers_rionegro' => $row[7],
            'male_newcomers_apartado' => $row[8],
            'female_newcomers_apartado' => $row[9],
            'male_former_students_poblado' => $row[10],
            'female_former_students_poblado' => $row[11],
            'male_former_students_rionegro' => $row[12],
            'female_former_students_rionegro' => $row[13],
            'male_former_students_apartado' => $row[14],
            'female_former_students_apartado' => $row[15],
            'male_total_students_poblado' => $row[16],
            'female_total_students_poblado' => $row[17],
            'male_total_students_rionegro' => $row[18],
            'female_total_students_rionegro' => $row[19],
            'male_total_students_apartado' => $row[20],
            'female_total_students_apartado' => $row[21],
            'total' => $row[22],
            'status' => $row[23]
        ]);
    }
}
