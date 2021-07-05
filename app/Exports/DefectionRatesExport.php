<?php

namespace App\Exports;

use App\Models\DefectionRate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DefectionRatesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DefectionRate::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Facultad',
            'Programa',
            'Semestre',
            'Matriculados Poblado',
            'Retiros Academicos Poblado',
            'Retiros Voluntarios Poblado',
            'Matriculados Rionegro',
            'Retiros Academicos Rionegro',
            'Retiros Voluntarios Rionegro',
            'Matriculados Apartado',
            'Retiros Academicos Apartado',
            'Retiros Voluntarios Apartado',
            'Matriculados Total',
            'Retiros Academicos Total',
            'Retiros Voluntarios Total',
            'Tasa de Desercion',
            'Status',
        ];
    }
}
