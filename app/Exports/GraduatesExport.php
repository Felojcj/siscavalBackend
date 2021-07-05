<?php

namespace App\Exports;

use App\Models\Graduate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GraduatesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Graduate::all();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Facultad',
            'Programa',
            'Semestre',
            'Total',
            'Status'
        ];
    }
}
