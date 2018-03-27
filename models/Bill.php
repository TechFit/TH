<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bill".
 *
 * @property int $user_id
 * @property int $total
 * @property int $updated_at
 *
 * @property User $user
 */
class Bill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'total', 'updated_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'total' => 'Total',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function create($user_id)
    {
        $model = new self([
            'user_id' => $user_id,
        ]);

        return $model->save(false);
    }

    /**
     * For sender - money take away. Method can be used for additional requirements
     *
     * @param $sender_id
     * @param $amount double
     * @return bool
     *
     */
    public function updateSenderBill($sender_id, $amount)
    {
        $senderBill = self::find()->where(['user_id' => $sender_id])->one();

        if ($senderBill) {
            $total = $senderBill->total - $amount;

            self::updateAll(['total' => $total], ['user_id' => $sender_id]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * For recipient - money add. Method can be used for additional requirements
     *
     * @param $recipient_id
     * @param $amount double
     * @return bool
     */
    public function updateRecipientBill($recipient_id, $amount)
    {
        $recipientBill = self::find()->where(['user_id' => $recipient_id])->one();

        if ($recipientBill) {

            $total = $recipientBill->total + $amount;

            self::updateAll(['total' =>  $total], ['user_id' => $recipient_id]);

            return true;
        } else {
            return false;
        }
    }

}
