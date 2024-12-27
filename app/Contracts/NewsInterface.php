<?php
namespace App\Contracts;

interface NewsInterface{

    public function allNews();
    public function fetchSingleNews($id);
    public function createNews(array $data);
    public function updateNews(array $data, $id);
    public function deleteNews($id);

    
}