<?php
$API_KEY = "c2dff7c1beb84f1986f343c4ea52077d";
$BASE_URL = "https://newsapi.org/v2/";
$BASE_TOPIC = "php";
$DB_USER="root";
$DB_PASSWORD="mypassword";
$DB_URL="mysql:host=127.0.0.1;dbname=postmetria";
$DB_CONNECTION = null;

/**
 *  Connect to DataBase And run starter script
 */
try {
    $DB_CONNECTION = new PDO($DB_URL, $DB_USER, $DB_PASSWORD);
    if ($DB_CONNECTION) {
        echo "Connected to the database successfully!\n\n";
        echo "Running stater Script\n\n";
        $query = file_get_contents("init.sql");
        $stmt = $DB_CONNECTION->prepare($query);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

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
    public DateTime $publishedAt;
    public $content;
    public ArticleSourceDTO $source;
}


echo "Start Processing...\n\n";


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
    $article->publishedAt = new DateTime($source->publishedAt);
    $article->title = $source->title;
    $article->url = $source->url;
    $article->urlToImage = $source->urlToImage;
    $article->description = $source->description;
    return $article;
}

function saveArticle(ArticleDTO $article,PDO $DB_CONNECTION) {
    $sourceId = saveSource($article->source, $DB_CONNECTION);
    $data = array(
        "author" => $article->author,
        "title" => $article->title,
        "description" => $article->description,
        "url" => $article->url,
        "urlToImage" => $article->urlToImage,
        "publishedAt" => $article->publishedAt->format('Y-m-d H:m:s'),
        "content" => $article->content,
        "sourceId" => $sourceId == -1 ? null : $sourceId
    );
    $sql = "INSERT INTO postmetria.tb_article (author, title, description, url, urlToImage, publishedAt, content, source_id) VALUES (:author, :title, :description, :url, :urlToImage, :publishedAt, :content, :sourceId)";
    executeQuery($sql, $data, $DB_CONNECTION);
}

function saveSource(ArticleSourceDTO $source, PDO $DB_CONNECTION): int {
    if ($source == null) {
        return -1;
    }
    $data = array("externalId" => $source->externalId, "name" => $source->name);
    $sql = "INSERT INTO postmetria.tb_article_source (externalId, name) VALUES (:externalId, :name)";
    executeQuery($sql, $data, $DB_CONNECTION);
    return $DB_CONNECTION->lastInsertId();
}

function executeQuery(string $query, array $data, PDO $DB_CONNECTION) {
    $smt = $DB_CONNECTION->prepare($query);
    $smt->execute($data);
}

function setParams($smt, $parameters = array()) {
    foreach ($parameters as $key => $value) {
        bindParam($smt, $key, $value);
    }
}

function bindParam($statement, $key, $value) {
    $statement->bindParam($key, $value);
}

$data = array(
    'q' => $BASE_TOPIC,
    'apiKey' => $API_KEY,
    'pageSize' => 100
);

echo "Sending request to " . buildUrl($BASE_URL . "everything", $data) . "...\n\n";

$response = file_get_contents(buildUrl($BASE_URL . "everything", $data));

if (empty($response)) {
    exit();
}
$parsedResponse = json_decode($response)->articles;
$length = count($parsedResponse);

$count = 0;
echo "Processing $length of data";
$array = array();
foreach ($parsedResponse as &$news) {
    $source = bindSourceObject($news->source);
    $article = bindArticleObject($news);
    $article->source = $source;
    saveArticle($article, $DB_CONNECTION);
}
echo "End of processing";

