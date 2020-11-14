<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){

        

       
        return view('welcome')  
            ->with('posts' ,Post::searched()->simplePaginate(3)) 
            ->with('tags' , Tag::all())                    
            ->with('categories' , Category::all());
        
    }
}
