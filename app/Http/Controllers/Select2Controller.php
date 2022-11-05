<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Category,
    Item,
    Stock
};

class Select2Controller extends Controller
{
    public function categories(Request $request)
    {
        $data = [];
        if ($request->category) {
            $data = Category::where('group_by', $request->category)
                            ->where('category_id', $request->category_id ?? null)
                            ->get()->toArray();
        }


        return response()->json($data);
    }

    public function stocks(Request $request)
    {
        $data = Stock::all()->toArray();


        return response()->json($data);
    }

    public function items(Request $request)
    {
        $data = Item::all()->toArray();

        return response()->json($data);
    }
}
