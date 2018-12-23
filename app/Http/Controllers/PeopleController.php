<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\People;
use App\Http\Resources\People as PeopleResource;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get people
        $people = People::orderBy('id', 'desc')->paginate(3);
        

        

        //return collection of people as a resource
        return PeopleResource::collection($people);


        /* $response = [
            'pagination' => [
                'total' => $people->total(),
                'per_page' => $people->perPage(),
                'current_page' => $people->currentPage(),
                'last_page' => $people->lastPage(),
                'from' => $people->firstItem(),
                'to' => $people->lastItem()
            ],
            'data' => $people
        ];
        return response()->json($response); */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Not using this, When creating I will use store ()
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $person = $request->isMethod('put') ? People::findOrFail($request->person_id) : new People;

        $person->id = $request->input('person_id');
        $person->first_name = $request->input('first_name');
        $person->last_name = $request->input('last_name');
        $person->birthday = $request->input('birthday');

        if($person->save()){
            return new PeopleResource($person);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get person
        $person = People::findOrFail($id);

        //return single article as a resource
        return new PeopleResource($person);

        // return new PeopleResource(
        //     filter(People::find($id))
        // );
    }
//  Super Wrong
    public function showFirstName($first_name)
    {
        // var_dump($first_name); die;
        
        //get person
        // $person = People::where('first_name', 'like','%'.$first_name.'%')->get();
        return PeopleResource::collection(
            People::where('first_name', 'like','%'.$first_name.'%')
                ->orderBy('id','desc')
                ->limit(3)
                ->get()
        );

        // $person = DB::table('people')
        //                 ->select(DB::raw('*, TIMESTAMPDIFF(YEAR, DATE(birthday), now()) AS age'))
        //                 ->where('first_name', 'like','%'.$first_name.'%')
        //                 ->orderBy('id','desc')
        //                 ->limit(3)
        //                 ->get();
        
        //return single article as a resource
        // return $person;
    } 

    public function showLastName($last_name)
    {
        // var_dump($first_name); die;
        
        //get person
        // $person = People::where('last_name', 'like','%'.$last_name.'%')->get();
        return PeopleResource::collection(
            People::where('last_name', 'like','%'.$last_name.'%')
                ->orderBy('id','desc')
                ->limit(3)
                ->get()
        );
        
        //return single article as a resource
        // return $person;
    } 



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Not using this, When creating I will use store ()
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Not using this, When creating I will use store ()
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //get person
        $person = People::findOrFail($id);

        if($person->delete()){
            return new PeopleResource($person);
        }
    }
}
