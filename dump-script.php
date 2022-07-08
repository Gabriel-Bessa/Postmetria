<?php
static $API_KEY = "c2dff7c1beb84f1986f343c4ea52077d";
static $BASE_URL = "https://newsapi.org/v2/";
static $BASE_TOPIC = "php";

class ArticleSourceDTO {
    public $name;
    public $externalId;
}

class ArticleDTO {
    public $author;
    public $title;
    public $description;
    public $url;
    public $urlToImage;
    public $publishedAt;
    public $content;
    public ArticleSourceDTO $source;
}

function buildUrl(string $BASE_URL, array $params): string {
    return $BASE_URL . (empty($params) ? "" : "?") . http_build_query($params);
}

function bindSourceObject($obj): ArticleSourceDTO {
    $source = new ArticleSourceDTO();
    $source->externalId = $obj->id;
    $source->name = $obj->name;
    return $source;
}

function bindArticleObject($source) : ArticleDTO {
    $article = new ArticleDTO();
    $article->author = $source->author;
    $article->content = $source->content;
    $article->publishedAt = $source->publishedAt;
    $article->title = $source->title;
    $article->url = $source->url;
    $article->urlToImage = $source->urlToImage;
    $article->description = $source->description;
    return $article;
}

$data = array(
    'q' => $BASE_TOPIC,
    'apiKey' => $API_KEY,
    'pageSize' => 10
);

$response = file_get_contents(buildUrl($BASE_URL . "everything", $data));
if (empty($response)) {
    exit();
}

$parsedResponse = json_decode($response)->articles;


$array = array();
foreach ($parsedResponse as &$news) {
    $source = bindSourceObject($news->source);
    $article = bindArticleObject($news);
    $article->source = $source;
    array_push($array, $article);
}

print_r($array);
