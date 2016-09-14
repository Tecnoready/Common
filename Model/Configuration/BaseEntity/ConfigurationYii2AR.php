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
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    
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
            [['key', 'name_wrapper', 'enabled'], 'required'],
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
            $now = new \DateTime();
            if(!$this->created_at){
                $this->created_at = $now->format(self::DATE_TIME_FORMAT);
            }
            $this->updated_at = $now->format(self::DATE_TIME_FORMAT);
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
        $now = new \DateTime();
        $this->created_at = $now->format(self::DATE_TIME_FORMAT);
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
        $now = new \DateTime();
        $this->updated_at = $now->format(self::DATE_TIME_FORMAT);
    }

    public function setValue($value) {
        $this->value = $value;
    }

}
