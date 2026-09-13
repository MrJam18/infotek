<?php

declare(strict_types=1);

namespace frontend\models\forms;

use common\models\Author;
use common\models\Guest;
use common\models\Subscription;
use yii\base\Model;

/**
 * Guest subscription form for an author.
 */
class SubscriptionForm extends Model
{
    public Author $author;
    public Guest $guest;
    public $phone = null;
    public function rules(): array
    {
        return [
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+7?[0-9]{10,15}$/', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * Creates a subscription; fills in the guest's phone on the first subscription.
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $guest = $this->guest;

        if ($guest->phone === null) {
            $guest->phone = $this->normalizePhone();
            if (!$guest->save()) {
                $this->addErrors($guest->getErrors());
                return false;
            }
        }

        $subscription = new Subscription([
            'guest_id' => $guest->id,
            'author_id' => $this->author->id,
        ]);

        if (!$subscription->save()) {
            $this->addErrors($subscription->getErrors());
            return false;
        }

        return true;
    }

    public function isAlreadySubscribed(): bool
    {
        if ($this->guest->getSubscriptions()->andWhere(['author_id' => $this->author->id])->exists()) {
            return true;
        }
        return false;
    }

    private function normalizePhone(): string
    {
        return (string) preg_replace('/\D/', '', (string) $this->phone);
    }
}
