<?php

namespace App\Exports;

use App\Models\NewStudent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewStudentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return NewStudent::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Faculad',
            'Programa',
            'Semestre',
            'Inscritos Poblado',
            'Admitidos Poblado',
            'Nuevos Poblado',
            'Inscritos Rionegro',
            'Admitidos Rionegro',
            'Nuevos Rionegro',
            'Inscritos Apartado',
            'Admitidos Apartado',
            'Nuevos Apartado',
            'Status'
        ];
    }
}
