<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Configuration\BaseEntity;

use Yii;

/**
 * Configuracion de yii2 active record
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @property string $key
 * @property string $value
 * @property string $name_wrapper
 * @property string $description
 * @property integer $enabled
 * @property string $created_at
 * @property string $updated_at
 */
class ConfigurationYii2AR extends \yii\db\ActiveRecord implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name_wrapper', 'enabled', 'created_at', 'updated_at'], 'required'],
            [['value'], 'string'],
            [['enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'description'], 'string', 'max' => 255],
            [['name_wrapper'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'value' => 'Value',
            'name_wrapper' => 'Name Wrapper',
            'description' => 'Description',
            'enabled' => 'Enabled',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if(!$this->created_at){
                $this->created_at = new \DateTime();
            }
            $this->updated_at = new \DateTime();
             return true;
         } else {
             return false;
         }
    }
    
    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function getKey() {
        return $this->key;
    }

    public function getNameWrapper() {
        return $this->name_wrapper;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function getValue() {
        return $this->value;
    }

    public function setCreatedAt() {
        $this->created_at = new \DateTime();
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setEnabled($enabled) {
        $this->enabled = (bool)$enabled;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function setNameWrapper($nameWrapper) {
        $this->name_wrapper = $nameWrapper;
    }

    public function setUpdatedAt() {
        $this->updated_at = new \DateTime();
    }

    public function setValue($value) {
        $this->value = $value;
    }

}
