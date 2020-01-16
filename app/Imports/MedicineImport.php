<?php

namespace App\Imports;

use App\medicine;
use Maatwebsite\Excel\Concerns\ToModel;

class MedicineImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[1]=='用处'){
            return null;
        }
        $medicine = medicine::where('name',$row[0])->first();
        if(!empty($medicine))
            return null;
        if(empty($row[0])||empty($row[2])||empty($row[3]))
            return null;
        return new medicine([
            'name'     => $row[0],
            'usage'     => $row[1],
            'weight'     => $row[2],
            'price'     => $row[3],
            'min_weight'     => $row[4],
            'max_weight'     => $row[5]
        ]);
    }
}
