<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppServices\News\ArticleFetcherService;

class NewsController extends Controller
{
    //
 public function __construct(ArticleFetcherService $article){
    $this->article = $article;
 }

 public function index(Request $request){
    return $this->article->searchArticles($request);
 }
 
}
