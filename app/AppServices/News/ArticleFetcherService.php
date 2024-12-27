<?php
namespace App\AppServices\News;

use Illuminate\Support\Facades\Http;
use App\Models\News;
use App\Contracts\NewsInterface;
use App\AppServices\Utility\DateTimeConfig;

class ArticleFetcherService
{



    public function __construct(NewsInterface $news, DateTimeConfig $date){

        $this->news = $news;
        $this->date = $date;

        $this->sources = [
            'guardian' => 'https://content.guardianapis.com/search?api-key='.env('THE_GUARDIAN_API_KEY'),
            'newsapi' => 'https://newsapi.org/v2/everything?apiKey='.env('NEWSAPI_DOTORG_KEY').'&q=latest',
            'nytimes' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json?api-key='.env('NY_TIMES_API_KEY'),
        ];

        $this->result = (object)array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data' => (object) null,
            'token' => null,
            'debug' => null
        );
    }
    

    public function fetchAndStoreArticles()
    {
        foreach ($this->sources as $sourceName => $url) {
            $response = Http::get($url);

            if ($response->successful()) {
                $articles = $this->formatArticles($response->json(), $sourceName);
                $this->storeArticles($articles);
            }
        }
    }

    private function formatArticles(array $data, string $sourceName): array
    {
        // Format articles based on the source
        $formatted = [];

        if ($sourceName === 'guardian') {
            foreach ($data['response']['results'] as $article) {
                $formatted[] = [
                    'title' => $article['webTitle'],
                    'description' => null,
                    'author' => null,
                    'source' => $sourceName,
                    'url' => $article['webUrl'],
                    'image_url' => null,
                    'published_at' => $this->date->getDateTime($article['webPublicationDate']),
                ];
            }
        } elseif ($sourceName === 'newsapi') {
            foreach ($data['articles'] as $article) {
                $formatted[] = [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'author' => $article['author'],
                    'source' => $sourceName,
                    'url' => $article['url'],
                    'image_url' => $article['urlToImage'],
                    'published_at' => $this->date->getDateTime($article['publishedAt']),
                ];
            }
        } elseif ($sourceName === 'nytimes') {
            foreach ($data['response']['docs'] as $article) {
                $formatted[] = [
                    'title' => $article['headline']['main'],
                    'description' => $article['abstract'],
                    'author' => $article['byline']['original'],
                    'source' => $sourceName,
                    'url' => $article['web_url'],
                    'image_url' => null,
                    'published_at' => $this->date->getDateTime($article['pub_date']),
                ];
            }
        }

        return $formatted;
    }

    private function storeArticles(array $articles)
    {
        foreach ($articles as $article) {
            $this->news->allNews()->updateOrCreate(
                ['url' => $article['url']], // Unique constraint
                $article
            );
        }
    }


public function searchArticles($request){

    $query = $this->news->allNews()->query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('source')) {
            $query->orWhere('source', $request->source);
        }

        if ($request->has('author')) {
            $query->orWhere('author', $request->author);
        }

        if ($request->has('date')) {
            $query->orWhereDate('published_at', $request->date);
        }

        if(count($query->get()) > 0){
            $this->result->status = true;
            $this->result->message = " Articles fetched";
            $this->result->data->values = $query->paginate(10);
            $this->result->status_code = 200;
            return response()->json($this->result, 200);
        }else{
            $this->result->status = true;
            $this->result->message = " No article found";
            $this->result->data->values = [];
            $this->result->status_code = 200;
            return response()->json($this->result, 200);
        }
        // return response()->json($query->get());

}

}
