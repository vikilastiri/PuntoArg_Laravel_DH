<?php

namespace App\Http\Controllers;

use App\Attraction;
use Illuminate\Http\Request;


class AttractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $attractions = Attraction::all();
      // $attractions->each(function($attractions){
      //   $attractions->name;
      //   $attractions->description;
      //   $attractions->featured_img;
      //   $attractions->category_id;
      //   $attractions->location_id;
      //
      // })
      //
      // return view('attraction')->with('attraction', $attractions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addAttractions');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //dd($request);
        //Paso 2: Antes vamos a validar los del formulario

        $rules = [ //https://laravel.com/docs/5.8/validation#rule-size
          "name" => "string|max:255|filled",
          "description" => "string",

          "featured_img" => "image",
        ];
        $messages = [
          "filled" => ":attribute no puede estar vacío.",
          "string" => ":attribute es debe ser texto.",
          "max" => ":attribute tiene un máximo de :max",
          "image"=>"el archivo debe ser del tipo .jpeg, .png, .bmp, .gif, o .svg"
        ];

        $this->validate($request, $rules, $messages); //Son 3 arrays asociativos

        //Paso 1:
        $attraction = new Attraction();

        //Paso 3 imagen:
        $route = $request->file('featured_img')->store('public/attractions');
        $fileName = basename($route); //
        $attraction->featured_img = $fileName;

        //Paso 1:
        $attraction->name = $request->name;
        $attraction->description = $request->description;

        // dd($request, $post);
        $attraction->save();

        return redirect('/');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $attraction= Post::find($id);
      $attraction->get();
        return view('attraction')->with('Attraction', $attraction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function edit(Attraction $attraction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attraction $attraction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attraction $attraction)
    {
        //
    }
    public function delete(Request $request){
      $id = $request->id;
      $attractionABorrar = Attraction::find($id);
      $attractionABorrar->delete();

      return redirect("/");

    }
}
