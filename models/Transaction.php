<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $recipient_id
 * @property double $amount
 * @property int $created_at
 *
 * @property User $recipient
 * @property User $sender
 */
class Transaction extends \yii\db\ActiveRecord
{
    const LIMITATION = -1000;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'amount'], 'required'],
            [['sender_id', 'recipient_id', 'created_at'], 'integer'],
            [['amount'], 'double'],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient',
            'amount' => 'Amount',
            'created_at' => 'Created At',
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Update total value on users bill
        $bill = new Bill();
        $bill->updateSenderBill(Yii::$app->user->identity->id, $this->amount);
        $bill->updateRecipientBill($this->recipient, $this->amount);
        $bill->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
}
