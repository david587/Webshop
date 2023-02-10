<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\newLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResources;

class ProductController extends BaseController
{
    //sort categories
    public function sortKeyboards()
    {
        $keyboard_id = Categorie::where("categorie","Keyboard")->first()->id;
        $products = Product::where("categorie_id",$keyboard_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    public function sortMouses()
    {
        $mouse_id = Categorie::where("categorie","Mouse")->first()->id;
        $products = Product::where("categorie_id",$mouse_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
        
    }

    public function sortHeadsets()
    {
        $headset_id = Categorie::where("categorie","Headset")->first()->id;
        $products = Product::where("categorie_id",$headset_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    public function sortMonitors()
    {
        $monitor_id = Categorie::where("categorie","Monitor")->first()->id;
        $products = Product::where("categorie_id",$monitor_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    //sort brands
    public function sortLogitech()
    {
        $logitech_id = Brand::where("brand","Logitech")->first()->id;
        $products = Product::where("brand_id",$logitech_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    public function sortHp()
    {
        $hp_id = Brand::where("brand","Hp")->first()->id;
        $products = Product::where("brand_id",$hp_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    public function sortUrage()
    {
        $urage_id = Brand::where("brand","Urage")->first()->id;
        $products = Product::where("brand_id",$urage_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    public function sortRedragon()
    {
        $redragon_id = Brand::where("brand","Redragon")->first()->id;
        $products = Product::where("brand_id",$redragon_id)->get();
        return $this->sendResponse(ProductResources::collection( $products ), "OK");
    }

    //product CRUD
    //in request->pagination number, if we not providing request the dafult value will be 10
    // $request->page
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->page ?? 1;
        $products = Product::paginate($perPage, ['*'], 'page', $page);
        return $this->sendResponse(ProductResources::collection($products), 'OK');
    }
    

    public function home()
    {
        $products = Product::all();
        if ($products->count() < 4) {
            return $this->sendResponse([], "There are less than 4items");
        }
        
        $randomProducts = $products->random(4);
        if($randomProducts)
        return $this->sendResponse(ProductResources::collection($randomProducts), "Ok");

    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input["brand_id"] = Brand::where("brand",$input["brand_id"])->first()->id;
        $input["categorie_id"] = Categorie::where("categorie",$input["categorie_id"])->first()->id;
        $validator = Validator::make($input, [
            "name"=>"required",
            "price"=>"required",
            "details"=>"required",
            "image"=>"required",
            "inStock"=>"required",
            "brand_id"=>"required",
            "categorie_id"=>"required"
        ]);
        if( $validator->fails()){
            return $this->sendError( $validator->errors());
        }

        $product = Product::create($input);
        return $this->sendResponse(new ProductResources($product), "Product Létrehozva");
    }

    public function show($id)
    {
        $product = Product::find($id);

        if(is_null($product))
        {
            return $this->sendError("Product nem létezik");
        }

        return $this->sendResponse(New ProductResources($product), "Ok");
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            "name"=>"required",
            "price"=>"required",
            "details"=>"required",
            "image"=>"required",
            "inStock"=>"required"
        ]);

        if($validator->fails())
        {
            return $this->sendError($validator->errors());
        }

        $product = Product::find($id);
        $product->update($request->all());

        return $this->sendResponse(new ProductResources($product), "Product frissitve");
    }

    public function destroy($id)
    {
        Product::destroy($id);

        return $this->sendResponse([],"Product törölve");
    }

    public function search(Request $request)
    {
        $input = $request->name;
        //i have to null the wariable if the input is not coming for brand table
        $brand = Brand::where("brand", $input)->first();
        $brand_id = $brand ? $brand->id : null;
        
        //same happening here
        $categorie = Categorie::where("categorie", $input)->first();
        $categorie_id = $categorie ? $categorie->id : null;
        
        //Sorting products
        $sortedProducts = Product::Where("name","like", "%".$input."%")->
        orwhere("categorie_id",$categorie_id)->
        orwhere("brand_id",$brand_id)
        ->get();
        return $this->sendResponse(ProductResources::collection( $sortedProducts ), "OK");
    }
}
