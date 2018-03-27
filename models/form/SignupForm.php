<?php
namespace app\models\form;

use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'trim'],
            [['username'], function($attribute) {
                $this->$attribute = HtmlPurifier::process($this->$attribute);
            }],

            [['username'], 'required'],

            ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'This username is already in use.')],

            ['username', 'string', 'min' => 2],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username')
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;

        if($user->save(false)) {
            return $user;
        }

        return null;
    }
}
