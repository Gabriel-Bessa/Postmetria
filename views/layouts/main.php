<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap4\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Html::encode($this->title) ?></title>

    <h1>Api - Dev Junior Gabriel Bessa</h1>
    <h3>Articles Paths:</h3>
    <h4>/api/article</h4>
    <h4>/api/article/create</h4>
    <h4>/api/article/delete/{id}</h4>
    <h4>/api/article/update/{id}</h4>
    <br>
    <h3>Articles Source Paths:</h3>
    <h4>/api/article-source</h4>
    <h4>/api/article-source/create</h4>
    <h4>/api/article-source/delete/{id}</h4>
    <h4>/api/article-source/update/{id}</h4>
</head>
<body class="d-flex flex-column h-100">
<style>

</style>
</body>
</html>
