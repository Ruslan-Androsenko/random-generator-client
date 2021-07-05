<?php

namespace app\models;

use Yii;
use \yii\base\Model;

/**
 *
 * @property int $id
 * @property string $name
 *
 */
class IpAddresses extends Model
{
    public $id;
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'IP-адрес',
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $patternFragment = '((25[0-5])|(2[0-4]\d)|(1\d{2})|(\d{1,2}))';
        $patternIp = "/($patternFragment\.){3}$patternFragment/";
        preg_match($patternIp, $this->name, $matches);

        return strcmp($this->name, $matches[0]) == 0;
    }
}
