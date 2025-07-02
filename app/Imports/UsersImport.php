<?php
namespace App\Imports;

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public $collection;

    public function collection(Collection $rows)
    {
        // Get the first row as headers
        $headers = $rows[0]->toArray();

        // Remove the header row from the data
        $dataRows = $rows->slice(1);

        // Map each row to an object with property names
        $this->collection = $dataRows->map(function($row) use ($headers) {
            $rowArray = $row->toArray();
            $assoc = [];
            foreach ($headers as $i => $field) {
                $assoc[$field] = $rowArray[$i] ?? null;
            }
            return (object) $assoc;
        });
    }
}