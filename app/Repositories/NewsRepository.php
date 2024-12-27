<?php
namespace App\Repositories;

use App\Contracts\NewsInterface;
use App\Models\News;


class NewsRepository implements NewsInterface{//implement the news interface
    
    public function __construct(News $news){
        $this->news = $news;
        
      }
  
      public function allNews(){
          return $this->news;
      }
      
      public function fetchSingleNews($id){
          return $this->news->whereId($id);
      }

      public function createNews(array $data){
          return $this->news->create($data);
      }
  
      public function updateNews(array $data, $id){
         return $this->news->whereId($id)->update($data);
      }
  
      public function deleteNews($id){
          return $this->news->whereId($id)->delete();
      }



}