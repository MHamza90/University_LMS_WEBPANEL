<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PdfToExcelExport implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    
}


