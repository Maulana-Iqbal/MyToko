<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BlameableObserver
{
    /**
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if(!empty(Auth::user()->id)){
            $id=Auth::user()->id;    
        }else{
           $id=1; 
        }
        $model->created_by = $id;
        $model->updated_by = $id;
    }

    /**
     * @param Model $model
     */
    public function updating(Model $model)
    {
        if(!empty(Auth::user()->id)){
            $id=Auth::user()->id;    
        }else{
           $id=1; 
        }
        $model->updated_by = $id;
    }
}