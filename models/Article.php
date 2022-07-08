<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_article".
 *
 * @property int $id
 * @property string $author
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $urlToImage
 * @property string|null $publishedAt
 * @property string $content
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author', 'title', 'description', 'url', 'urlToImage', 'content'], 'required'],
            [['description', 'content'], 'string'],
            [['publishedAt'], 'safe'],
            [['author', 'title', 'url', 'urlToImage'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Author',
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'urlToImage' => 'Url To Image',
            'publishedAt' => 'Published At',
            'content' => 'Content',
        ];
    }
}
