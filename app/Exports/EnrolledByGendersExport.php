<?php

namespace App\Exports;

use App\Models\EnrolledByGender;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnrolledByGendersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EnrolledByGender::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Facultad',
            'Programa',
            'Semestre',
            'Hombres Nuevos Poblado',
            'Mujeres Nuevas Poblado',
            'Hombres Nuevos Rionegro',
            'Mujeres Nuevas Rionegro',
            'Hombres Nuevos Apartado',
            'Mujeres Nuevas Apartado',
            'Hombres Antiguos Poblado',
            'Mujeres Antiguas Poblado',
            'Hombres Antiguos Rionegro',
            'Mujeres Antiguas Rionegro',
            'Hombres Antiguos Apartado',
            'Mujeres Antiguas Apartado',
            'Total Estudiantes Hombres Poblado',
            'Total Estudiantes Mujeres Poblado',
            'Total Estudiantes Hombres Rionegro',
            'Total Estudiantes Mujeres Rionegro',
            'Total Estudiantes Hombres Apartado',
            'Total Estudiantes Mujeres Apartado',
            'Total',
            'Status'
        ];
    }
}
