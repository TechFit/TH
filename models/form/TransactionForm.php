<?php


namespace app\models\form;


use app\models\Bill,
    app\models\Transaction,
    app\models\User;
use yii\base\Model;

/**
 * Class TransactionForm
 *
 * Form for transaction between users
 *
 * @package app\models\form
 */
class TransactionForm extends Model
{
    public $recipient;

    public $amount;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['recipient', 'amount'], 'required'],
            [['recipient'], 'string'],
            [['amount'], 'validateAmount'],
        ];
    }

    public function validateAmount($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $userBill = Bill::find()->where(['user_id' => \Yii::$app->user->identity->id])->one();

            $check = preg_match('/^\d+(\.\d{2})?$/', $this->amount);

            // Check input amount for correct value
            if (!$check) {
                $this->addError($attribute, 'Incorrect amount.');
            }

            // Check if sender have bill amount < Limit
            if ($userBill->total < Transaction::LIMITATION || ($userBill->total - $this->amount) < Transaction::LIMITATION) {
                $this->addError($attribute, 'Limit -1000 exceeded.');
            }
        }
    }


    /**
     * @return bool|null
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function send()
    {
        if ($this->validate()) {

            $transaction = new Transaction();
            $transaction->sender_id = \Yii::$app->user->identity->id;
            $transaction->amount = $this->amount;

            // If user from select2 is exist in DB than save transaction value
            if (User::find()->where(['username' => $this->recipient])->exists()) {

                $transaction->recipient_id = $this->recipient;

                $transaction->save();

                return true;
            } else {

                // If user from select2 not exist in DB than save transaction value with DB-transaction (save user, than save transaction)
                $user = new User();
                $user->username = $this->recipient;

                $t = User::getDb()->beginTransaction();

                try {
                    if (!$user->save()) {
                        throw new \Exception('User not saved');
                    }

                    $transaction->recipient_id = $user->id;

                    if (!$transaction->save()) {
                        throw new \Exception('Transaction not saved');
                    }

                    $t->commit();

                    return true;

                } catch (\Exception $e) {
                    $t->rollBack();
                    throw $e;
                }
            }
        }

        return false;
    }
}