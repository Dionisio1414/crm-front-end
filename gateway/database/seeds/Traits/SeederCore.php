<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait SeederCore
{
    public function headers($headers, $table, $is_edit=false)
    {
        if($is_edit) {
            /* Update titles or new insert */
            foreach ($headers as $header)
            {
                if(!DB::table($table)->find($header['id'])){
                    DB::table($table)->updateOrInsert(['id'=>$header['id']],$header);
                }else{
                    if(isset($header['title'])){
                        $data['title'] =  $header['title'];
                        if(isset($header['value'])){
                            $data['value'] = $header['value'];
                        }

                        DB::table($table)->updateOrInsert(['id'=>$header['id']],$data);
                    }
                }
                $headerIds[] = $header['id'];
            }

            /* Check and remove headers */
            if($headerIds){
                DB::table($table)->whereNotIn('id', $headerIds)->delete();
            }
        }else{
            /* Reset to default or new insert */
            foreach ($headers as $header)
            {
                DB::table($table)->updateOrInsert(['id'=>$header['id']],$header);
            }
        }
    }

    public function items($items, $table)
    {
        /* Reset to default or new insert */
        foreach ($items as $item)
        {
            DB::table($table)->updateOrInsert(['id'=>$item['id']],$item);
        }
    }

    public function details($details, $table, $is_edit=false)
    {
        if($is_edit) {
            /* Update titles or new insert */
            foreach ($details as $detail)
            {
                if(!DB::table($table)->find($detail['id'])){
                    DB::table($table)->updateOrInsert(['id'=>$detail['id']],$detail);
                }else{
                    if(isset($detail['title'])){
                        $data['title'] =  $detail['title'];
                        if(isset($header['value'])){
                            $data['value'] = $header['value'];
                        }

                        DB::table($table)->updateOrInsert(['id'=>$detail['id']],$data);
                    }
                }
            }
        }else{
            /* Reset to default or new insert */
            foreach ($details as $detail)
            {
                DB::table($table)->updateOrInsert(['id'=>$detail['id']],$detail);
            }
        }
    }
}
