<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\LoanRecord;
use App\Models\Stock;
use App\Models\StockLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')){
            $data = Item::where('name', 'like', '%' . request('search') . '%')->paginate(10);
        }

        if (!isset($request->stock_id) || empty($request->stock_id))
            return redirect()->back()->withErrors(['msg' => 'item dengan stock tersebut tidak ditemukan']);

        $data = Item::where('stock_id', $request->stock_id)
            ->paginate(5)
            ->withQueryString();

        return view('pages.ItemIndex', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $post = Item::find($id)->update($request->all());

        return back()->with('message', 'Data telah diperbaharui!');
    }

    public function show($id)
    {
        $data = Item::find($id);
        if (!$data) return redirect()->route('item.index');

        return view('pages.ItemIndex', compact('data'));
    }

    public function store(Request $request)
    {
        // $stock = Stock::find($request->stock_id)->first();
        $stock = DB::table('stocks')->where('id', $request->stock_id)->first();

        // dd($request->all());

        $req = $request->validate([
            'stock_id' => ['required', 'uuid', 'exists:stocks,id'],
            'unit_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                    $query->where('group_by', 'units');
                })
            ],
            'kind_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                    $query->where('group_by', 'kinds');
                })
            ],
            'merk_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                    $query->where('group_by', 'merks');
                })
            ],
            'name' => 'required',
            'code' => [
                Rule::requiredIf(fn () => $stock->type === 'asset'),
                'unique:items,code'
            ],
            'ukuran' => [
                'nullable', 'max:255'
            ],

            'amount' => [
                'nullable', 'numeric'
            ],
        ]);

        if ($stock->type == 'non-asset') {
            $amount = $req['amount'];
            unset($req['amount']);
        }

        $item = Item::create($req);

        if ($stock->type == 'asset') {
            // LoanRecord::create([
            //     'item_id' => $item->id,
            //     'is_in' => true,
            //     'notes' => 'init from system',
            //     'created' => now()
            // ]);
        } else {
            StockLog::create([
                'item_id' => $item->id,
                'user_id' => auth()->user()->id,
                'type' => 'in',
                'amount' => $amount,
                'moved_at' => now()
            ]);
        }


        return redirect()->back()->with('message', 'item berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Item::destroy($id);

        return redirect()->back()->with('message', 'Item berhasil dihapus.');
    }

    public function exportpdf(Request $request) //untuk cetak item asset
    {
        // return 'berhasil';

        $data = Item::where('stock_id', $request->stock_id)->get();
        $stock = \App\Models\Stock::find($request->stock_id);
        $header = $stock->type == 'asset' ? 'Daftar Asset' : 'Daftar Non-Asset';
        $header .= ' : ' . $stock->name;
        $division = $stock->division->label;

        $pdf = PDF::loadView('pdf.ItemsByStock', compact('data', 'header', 'division'));

        return $pdf->stream('items.pdf');
    }

    public function cetakpdf(Request $request) //untuk cetak item non-asset
    {
        $data = Item::where('stock_id', $request->stock_id)->get();
        $stock = \App\Models\Stock::find($request->stock_id);
        $header = $stock->type == 'non-asset' ? 'Daftar Asset' : 'Daftar Non-Asset';
        $header .= ' : ' . $stock->name;
        $division = $stock->division->label;

        $pdf = PDF::loadView('pdf.CetakByStock', compact('data', 'header', 'division'));

        return $pdf->stream('items.pdf');
    }
}
