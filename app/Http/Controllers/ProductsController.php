<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\Dealer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    public function index()
    {
        $no_part = Product::select('no_part')->distinct()->get()->toArray();
        $filters = [
            ['value' => null, 'text' => 'None'],
            ['value' => 'name_asc', 'text' => 'Sort by Name (A-Z)'],
            ['value' => 'name_desc', 'text' => 'Sort by Name (Z-A)'],
            // ['value' => 'price_asc', 'text' => 'Sort by Price (0-9)'],
            // ['value' => 'price_desc', 'text' => 'Sort by Price (9-0)']
            ['value' => 'price_asc', 'text' => 'Lowest Price'],
            ['value' => 'price_desc', 'text' => 'Highest Price'],
            ['value' => 'nearest_dealer', 'text' => 'Nearest Dealer'],
            ['value' => 'farthest_dealer', 'text' => 'Farthest Dealer']
        ];


        $stock_filter = [
            ['value' => null, 'text' => 'All'],
            ['value' => 'in_stock', 'text' => 'In Stock'],
            ['value' => 'out_of_stock', 'text' => 'Out of Stock']
        ];

        return view('product.index', compact('filters', 'stock_filter', 'no_part'));
    }

    public function productList(Request $request)
    {
        $sort = $request->get('sort'); // Filter pengurutan
        $search = $request->get('search'); // Filter pencarian
        $stock = $request->get('stock'); // Filter stok
        $no_part = $request->get('no_part'); // Filter nomor part
        $dealer = $request->get('dealer'); // Filter dealer
        $user = Auth::user();
        $kodeDealer = $user->kode_dealer;
        $cek_data_product_exists = DB::table('products')->exists();
        $cek_data_dealer_exists = DB::table('dealers')->exists();

        if (!$cek_data_product_exists || !$cek_data_dealer_exists) {
            $message = '';

            if (!$cek_data_product_exists && !$cek_data_dealer_exists) {
                $message = 'Data products dan dealers kosong! Silahkan Import Excel Data Products dan Data Dealers.';
            } elseif (!$cek_data_product_exists) {
                $message = 'Data products kosong! Silahkan Import Excel Data Products.';
            } elseif (!$cek_data_dealer_exists) {
                $message = 'Data dealers kosong! Silahkan Import Excel Data Dealers.';
            }

            return response()->json([
                'status' => 'empty',
                'data' => [],
                'message' => $message,
            ]);
        }

        $products = DB::table('products as p')
            ->leftJoin('dealers as d', 'p.kode_dealer', '=', 'd.kode')
            ->leftJoin('detail_product as pi', 'p.no_part', '=', 'pi.no_part')
            // ->leftJoin('product_image as pi', 'p.no_part', '=', 'pi.no_part')
            ->select(
                'p.id as product_id',
                'p.no_part',
                'p.nama_part',
                'p.standard_price_moving_avg_price',
                'p.oh',
                'p.kode_dealer',
                'p.deleted_at as product_deleted_at',
                'd.kode as dealer_kode',
                'd.ahass',
                'd.kota_kab',
                'd.deleted_at as dealer_deleted_at',
                'pi.image as product_image',
                'pi.functionality'
            )->whereNull('p.deleted_at');

        if ($dealer) {
            $products->where('p.kode_dealer', $dealer);
        }
        // Filter pencarian
        if ($search) {
            $lowerSearch = strtolower($search);
            $products->where(function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(p.nama_part) LIKE ?', ['%' . $lowerSearch . '%'])
                    ->orWhereRaw('LOWER(p.no_part) LIKE ?', ['%' . $lowerSearch . '%'])
                    ->orWhereRaw('LOWER(d.ahass) LIKE ?', ['%' . $lowerSearch . '%']);
            });
        }

        // Filter stok
        if ($stock) {
            if ($stock === 'in_stock') {
                $products->where('p.oh', '>', 0);
            } elseif ($stock === 'out_of_stock') {
                $products->where('p.oh', '=', 0);
            }
        }

        // Filter pengurutan
        if ($sort === 'nearest_dealer' || $sort === 'farthest_dealer') {
            $products->leftJoin('distance_order_dealer as dod', 'd.kota_kab', '=', 'dod.area')
                ->addSelect('dod.order_distance')
                ->where('dod.kode_dealer', $kodeDealer)
                ->whereNull('dod.deleted_at')
                ->whereNull('d.deleted_at')
                ->whereNull('p.deleted_at');

            $products->orderBy('dod.order_distance', $sort === 'nearest_dealer' ? 'asc' : 'desc');
        } else {
            switch ($sort) {
                case 'name_asc':
                    $products->orderBy('p.nama_part', 'asc');
                    break;
                case 'name_desc':
                    $products->orderBy('p.nama_part', 'desc');
                    break;
                case 'price_asc':
                    $products->orderBy('p.standard_price_moving_avg_price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('p.standard_price_moving_avg_price', 'desc');
                    break;
                default:
                    $products->orderBy('p.nama_part', 'asc');
                    break;
            }
        }

        // Filter nomor part
        if ($no_part) {
            $products->whereIn('p.no_part', $no_part);
        }
        // Pagination
        $products = $products->paginate(9);
        // Modify 'oh' field based on user role
        $products->getCollection()->transform(function ($product) use ($user) {
            if (!$user->hasRole('main_dealer')) {
                $product->oh = $product->oh > 0 ? 'Stock Available' : 'Out Of Stock';
            }
            return $product;
        });

        // Menyiapkan data untuk response
        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'current_range' => (($products->currentPage() - 1) * $products->perPage() + 1) . '-' .
                min($products->currentPage() * $products->perPage(), $products->total()),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
        ]);
    }

    public function filterNoPart(Request $request)
    {
        $no_part = $request->get('no_part');
        // $no_part = $request->get('no_part');
        $products = Product::select('no_part');

        if ($no_part) {
            $products->whereRaw('LOWER(no_part) LIKE ?', ['%' . strtolower($no_part) . '%']);
        }

        $products = $products->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function filterDealer(Request $request)
    {
        $dealer = $request->get('dealer');
        $data_dealer = Dealer::select('kode', 'ahass');

        if ($dealer) {
            $data_dealer->whereRaw('LOWER(ahass) LIKE ?', ['%' . strtolower($dealer) . '%'])->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($dealer) . '%']);
        }

        $data_dealer = $data_dealer->limit(10)->get();
        return response()->json([
            'success' => true,
            'data' => $data_dealer,
        ]);
    }
}
