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

class AccountChangeSeconderyEmailForm extends CFormModel {

    public $seconderyPassword;
    public $newSeconderyEmail;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
           array('seconderyPassword, newSeconderyEmail', 'required'),
//            array('seconderyPassword', 'CheckPasswordValidator'),
            array('newSeconderyEmail', 'email'),
            array('newSeconderyEmail', 'unique', 'attributeName' => 'email', 'caseSensitive' => false, 'className' => 'User', 'message' => '{attribute} "{value}" is already in use!'),
            array('newSeconderyEmail', 'unique', 'attributeName' => 'secondery_email', 'caseSensitive' => false, 'className' => 'User', 'message' => '{attribute} "{value}" is already in use!'),
        );
    }
    
    public function CheckPasswordValidator()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);
        if (empty($user->secondery_password)) {
            return true;
        } else {
            if ($user->secondery_password == $this->validPassword($this->seconderyPassword)) {
                return true;
            } else {
                $this->addError("seconderyPassword", "Password has not correct");
            }
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
