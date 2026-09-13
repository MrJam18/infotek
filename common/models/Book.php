<?php

declare(strict_types=1);

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $photo
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 * @property-read Author[] $authors
 */
class Book extends ActiveRecord
{

    /**
     * Event raised after a new book is saved and its authors are synchronized.
     */
    public const EVENT_CREATED = 'bookCreated';
    public array $authorsUpdated = [];

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'year', 'isbn', 'authorsUpdated'], 'required'],
            [['title'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['year'], 'integer', 'min' => 1000, 'max' => (int) date('Y') + 1],
            [['isbn'], 'unique'],
            [['isbn'], 'string', 'max' => 13],
            [['description'], 'string'],
            [['photo'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'year' => 'Год выпуска',
            'isbn' => 'ISBN',
            'description' => 'Описание',
            'photo' => 'Фото обложки'
        ];
    }



    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->trigger(self::EVENT_CREATED,);
        }
        $authors = ArrayHelper::getColumn($this->authors, ['id']);
        $containsAll = $authors && empty(array_diff($authors, $this->authorsUpdated));
        if (!$containsAll) {
            BookAuthor::deleteAll(['book_id' => $this->id]);
            $insert = [];
            foreach ($this->authorsUpdated as $author) {
                $insert[] = [
                    $this->id,
                    $author,
                ];
            }
            \Yii::$app->db->createCommand()->batchInsert('book_author', ['book_id', 'author_id'], $insert)->execute();
        }
    }

    protected function uploadFile(): bool
    {
        if (!isset($this->photoFile)) {
            return false;
        }
        $fileName = $this->photoFile->baseName . '.' . $this->photoFile->extension;
        $ret = $this->photoFile->saveAs('uploads/' . $this->photoFile->baseName . '.' . $this->photoFile->extension);
        if ($ret) {
            $this->photo = $fileName;
        }
        return $ret;
    }
}
