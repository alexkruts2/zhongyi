<?php

namespace App\Imports;

use App\contrary;
use Maatwebsite\Excel\Concerns\ToModel;

class ContraryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[1]=='药材名称'){
            return null;
        }
        if(empty($row[0]))
            return null;
        return new contrary([
            'name'     => $row[0],
            'contrary'     => $row[1]
        ]);
    }
}
