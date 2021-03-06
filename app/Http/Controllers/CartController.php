<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Voucher;
use App\User;
use Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $openCart = Cart::all()->where('user_id', '=', Auth::User()->id)->where('status', '=', 0);

      $totalCart = $openCart->reduce(function($total, $cart){
        $total += $cart->price;
        return $total;
      });
  ;
      return view('cart')->with('cart', $openCart)->with('total', $totalCart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
      $voucher = Voucher::find($id);
      $newItem = new Cart();

      $newItem->name = $voucher->name;
      $newItem->description = $voucher->description;
      $newItem->price = $voucher->price;
      // $newItem->featured_img = $voucher->featured_img;
      $newItem->cant = 1; //Vamos a hardcodear el número pero debería venir de un form o del array.
      $newItem->user_id = Auth::User()->id;
      //dd($newItem);

      $newItem->save();
      return redirect('/cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $item = Cart::find($id);
      $item->delete();
      return redirect('/cart');
    }

    public function closeCart()
    {
        //Traemos los carritos abiertos: status = 0;
        $openCart = Cart::all()->where('user_id', '=', Auth::User()->id)->where('status', '=', 0);

        //Identificamos cual es el último nro de carrito cerrado
        $cartNumber = Cart::max('cart_number');

        //Actualizamos carrito a "cerrado" y asignamos el nro de carrito a todos los items.

        foreach ($openCart as $item) {
            $item->cart_number = $cartNumber + 1;
            $item->status = 1;
            $item->save();
        }

        return view('thanks');

    }

    public function history()
    {
      $history = Cart::all()->where('user_id', '=', Auth::User()->id)->where('status', '=', 1)->groupBy('cart_number'); //Agrupamos por nro de carrito para mostrarlo en la vista.

      return view('history')->with('history', $history);

    }
}
