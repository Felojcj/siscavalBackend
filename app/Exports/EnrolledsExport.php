<?php

namespace App\Exports;

use App\Models\Enrolled;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnrolledsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Enrolled::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Facultad',
            'Programa',
            'Semestre',
            'Nuevos Poblado',
            'Antiguos Poblado',
            'Total Poblado',
            'Nuevos Rionegro',
            'Antiguos Rionegro',
            'Total Rionegro',
            'Nuevos Apartado',
            'Antiguos Apartado',
            'Total Apartado',
            'Gran Total',
            'Status'
        ];
    }
}
