<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ValidValue;
use Validator;

class ScheduleImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '0' => 'string',
        ])->validate();
    }
}
