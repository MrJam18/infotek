<?php

namespace frontend\models\forms;

use common\models\Book;
use common\models\validators\IsbnValidator;
use Yii;
use yii\web\UploadedFile;

class BookForm extends Book
{
    /** @var UploadedFile */
    public $photoFile;
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['isbn'], 'filter', 'filter' => [self::class, 'normalizeIsbn'], 'skipOnEmpty' => true],
            [['isbn'], IsbnValidator::class],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ]);
    }

    public function beforeValidate(): bool
    {
        $this->photoFile = UploadedFile::getInstance($this, 'photoFile');
        if (!$this->photoFile) {
            return false;
        }
        return parent::beforeValidate();
    }


    /**
     * Normalizes an ISBN: removes hyphens and spaces, and uppercases it.
     */
    public static function normalizeIsbn(mixed $value): string
    {
        return strtoupper((string) preg_replace('/[\s\-]/', '', (string) $value));
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert && !$this->user_id) {
            $this->user_id = Yii::$app->user->id;
        }
        return true;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            $this->deleteCurrentFile();
        }
        $this->uploadFile();
    }

    protected function uploadFile(): bool
    {
        if (!isset($this->photoFile)) {
            return false;
        }
        $fileName = $this->photoFile->baseName . '.' . $this->photoFile->extension;
        $path = $this->getFilePath($fileName);
        $ret = $this->photoFile->saveAs($path);
        if ($ret) {
            $this->photo = $fileName;
        }
        return $ret;
    }
}