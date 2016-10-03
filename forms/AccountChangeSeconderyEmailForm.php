<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016  Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences GNU AGPL v3
 *
 */

namespace humhub\modules\secondaryemail\forms;

use humhub\modules\user\models\Password;
use Yii;
use yii\base\Model;
use humhub\modules\user\models\User;

class AccountChangeSeconderyEmailForm extends Model {

    public $seconderyPassword;
    public $newSeconderyEmail;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
           array(['seconderyPassword', 'newSeconderyEmail'], 'required'),
            array('newSeconderyEmail', 'email'),
            array('newSeconderyEmail', 'unique', 'targetAttribute' => 'email', 'targetClass' => User::className(), 'message' => '{attribute} "{value}" is already in use!'),
            array('newSeconderyEmail', 'unique', 'targetAttribute' => 'secondary_email', 'targetClass' => User::className(), 'message' => '{attribute} "{value}" is already in use!'),
            array('seconderyPassword' , 'EqualFirstPassword')
        );
    }

    public function EqualFirstPassword()
    {
        $userPassword = Password::find()->andFilterWhere(['user_id' => Yii::$app->user->id])->one();

        if(!$userPassword->validatePassword($this->seconderyPassword)) {
            $this->addError("seconderyPassword", "Incorrect password has been entered");
        }
    }

    public function validPassword($password)
    {
        return hash('sha512', hash('whirlpool', $password));
    }
    
    public function generatePassword($password)
    {
        return hash('sha512', hash('whirlpool', $password));
    }
    
    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'seconderyPassword' => Yii::t('UserModule.forms_AccountChangeEmailForm', 'Secondary Password'),
            'newSeconderyEmail' => 'New Secondary Email',
        );
    }
}
