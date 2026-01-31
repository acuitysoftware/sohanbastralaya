<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EditProductStock;
use App\Models\Product;
use App\Models\ProductQuantity;

class ImportController extends Controller
{
    public function import()
    {
        $data['title'] = 'Product List';
        return view('pages.product.import', $data);
    }
    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file); // skip header row

        while (($data = fgetcsv($file)) !== false) {
            if ($data[1]) {

                $product = Product::where('product_code', $data[1])->first();
                if ($product) {
                    $updateProductQuantity = null;
                    $bar_code = null;
                    if (isset($data[0])) {
                        $bar_code = $data[0];
                    }
                    if ($product->quantity == $data[2]) {
                        $product->update(['bar_code' => $bar_code]);
                    } elseif ($product->quantity > $data[2]) {
                        $updateProductQuantity = ($product->quantity - $data[2]);
                        EditProductStock::create([
                            'product_id' => $product->id,
                            'pro_name' =>  $product->name,
                            'qty' => $updateProductQuantity,
                            'date' => date('Y-m-d'),
                        ]);
                        $product->update([
                            'bar_code' => $bar_code,
                            'quantity' => $data[2],
                        ]);
                    } elseif ($product->quantity < $data[2]) {
                        $updateProductQuantity = ($data[2] - $product->quantity);
                        ProductQuantity::create([
                            'product_id' => $product->id,
                            'quantity' => $updateProductQuantity,
                            'date' => date('Y-m-d'),
                            'time' => date('Y-m-d H:i:s'),
                        ]);
                        $product->update([
                            'bar_code' => $bar_code,
                            'quantity' => $data[2],
                        ]);
                    }
                }
            }
        }

        dd($file);

        fclose($file);
        dd($request->all());
    }
}
