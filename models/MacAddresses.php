<?php

namespace app\models;

use Yii;
use \yii\base\Model;

/**
 *
 * @property int $id
 * @property string $name
 * @property int $ip_address_id
 * @property int $status
 * @property int $attempts
 * @property string $ip
 *
 */
class MacAddresses extends Model
{
    public $id;
    public $name;
    public $ip_address_id;
    public $status;
    public $attempts;
    public $ip;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ip_address_id'], 'required'],
            [['id', 'ip_address_id', 'status', 'attempts'], 'integer'],
            [['name', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'MAC-адрес',
            'ip_address_id' => 'Ip Address ID',
            'status' => 'Статус',
            'attempts' => 'С какой попытки был создан уникальный Mac-адрес',
            'ip' => 'IP-адрес',
        ];
    }
}
