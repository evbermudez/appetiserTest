<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class People extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        // var_dump($request); die;
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'age' => \Carbon::parse($this->birthday)->age
        ];
    }

    // public function with($request){
    //     return [
    //         'author' => 'Eric Bermudez'
    //     ];
    // }
}
