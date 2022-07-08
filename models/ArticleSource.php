<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_article_source".
 *
 * @property int $id
 * @property string|null $externalId
 * @property string $name
 *
 * @property TbArticle[] $tbArticles
 */
class ArticleSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_article_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['externalId', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'externalId' => 'External ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[TbArticles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTbArticles()
    {
        return $this->hasMany(TbArticle::className(), ['source_id' => 'id']);
    }
}
