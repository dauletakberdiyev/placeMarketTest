<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Http\Request;
use App\DTO\ProductDTO;
use Validator;


class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'picture' => 'required',
            'desc' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $productDTO = new ProductDTO(
            $request->input('name'),
            $request->input('price'),
            $request->input('picture'),
            $request->input('desc')
        );

        $product = new Product();
        $product->name = $productDTO->name;
        $product->price = $productDTO->price;
        $product->picture = $productDTO->picture;
        $product->desc = $productDTO->desc;
        $product->save();

        return $this->sendResponse($product->toArray(), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        return $this->sendResponse($product->toArray(), 'Product retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'price' => 'required',
            'picture' => 'required',
            'desc' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $productDTO = new ProductDTO(
            $request->input('name'),
            $request->input('price'),
            $request->input('picture'),
            $request->input('desc')
        );

        $product->name = $productDTO->name;
        $product->price = $productDTO->price;
        $product->picture = $productDTO->picture;
        $product->desc = $productDTO->desc;
        $product->save();
        
        return $this->sendResponse($product->toArray(), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse($product->toArray(), 'Product deleted successfully.');
    }

    public function addToBasket($id){
        // session()->flush();
        $product = Product::findOrFail($id);
        $basket = session()->get('basket');
        
        if(isset($basket[$id])){
            return $this->sendResponse($basket, 'Product already in basket');
        }

        $basket[$id] = [
            'name' => $product->name,
            'price' => $product->price,
            'picture' => $product->picture
        ];

        session()->put('basket', $basket);
        return $this->sendResponse($basket, 'Product successfully added to basket');
    }

    public function removeFromBasket($id){
        $basket = session()->get('basket');

        if(!isset($basket[$id])){
            return $this->sendError('Existing Error', 'Item does not exist in basket');
        }

        unset($basket[$id]);
        session()->put('basket', $basket);

        return $this->sendResponse($basket, 'Product successfully removed from basket');
    }
}
