<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Category, Tag, Post};
use App\Models\Portofolio;
use App\Models\Kategori;
use App\Models\Slide;
use App\Models\Produk;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;

class FrontController extends Controller
{
    protected $stockRepo;
    public function __construct()
    {
        $this->stockRepo=new StockRepositori();
    }

    public function home(){
    $portofolio = Portofolio::orderBy('created_at', 'desc')->get();
	$kategori = Kategori::get();
	$slider = Slide::where('isActive', 1)->get();
	$produkBaru = $this->stockRepo->terbaru()->take(8);
	$posts = Post::latest()->limit(6)->get();
	return view('website.data-index', compact('kategori', 'portofolio', 'slider', 'produkBaru', 'posts'));
    }

    public function index()
    {
        $posts = Post::latest()->paginate(10);
        $allCategory = Category::withCount('posts')->get();
        $recent = Post::latest()->limit(5)->get();
        $allTag=Tag::all();
        return view('blog.blog', compact('posts','allCategory','recent','allTag'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $allCategory = Category::withCount('posts')->get();
        $recent = Post::latest()->limit(5)->get();
        $allTag=Tag::all();
        return view('blog.blog-detail', compact('post','allCategory','recent','allTag'));
    }

    public function category(Category $category)
    {
        $posts = $category->posts()->latest()->get();
        $allCategory = Category::withCount('posts')->get();
        $recent = Post::latest()->limit(5)->get();
        $allTag=Tag::all();
        return view('blog.blog', compact('posts','allCategory','recent','allTag'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()->latest()->get();
        return view ('welcome',compact('tag','posts'));
    }

    public function eshop(){
        return view('eshop.home');
    }

}
