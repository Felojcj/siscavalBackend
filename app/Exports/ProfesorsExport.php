<?php

namespace App\Exports;

use App\Models\Profesor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfesorsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Profesor::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Semestre',
            'Sede',
            'Facultad',
            'Nivel de Formacion',
            'Dedicacion',
            'Total',
            'Status',
        ];
    }
}
