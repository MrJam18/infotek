<?php

namespace common\notifications;

use common\models\Book;
use common\models\Subscription;
use common\notifications\base\BookNotifierInterface;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class SmsBookNotifier extends Component implements BookNotifierInterface
{
    public function notify(Book $book): void
    {
        $smsClient = new SMSPilot();
        $subscriptions = Subscription::findAll([
            'author_id' => ArrayHelper::getColumn($book->authors, 'id')
        ]);

        foreach ($subscriptions as $subscription) {
            if ($subscription->guest->phone) {
                $text = 'Уведомляем о новой книге ' .  $book->title . ' от автора ' . $subscription->author->full_name . ' на которого вы подписались';
                try {
                    $smsClient->send($subscription->guest->phone, $text);
                } catch (\Throwable $e) {
                    Yii::error('Ошибка отправки уведомления: ' . $e->getMessage(), __METHOD__);
                }
            }
        }
    }
}