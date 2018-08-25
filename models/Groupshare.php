<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groupshare".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property double $value
 * @property string $decription
 * @property string $publishTime
 * @property string $createTime
 * @property string $lastUpdateTime
 *
 * @property Catchshare[] $catchshares
 * @property Payment[] $payments
 */
class Groupshare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groupshare';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['value'], 'number'],
            [['decription'], 'string'],
            [['publishTime', 'createTime', 'lastUpdateTime'], 'safe'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'value' => 'Value',
            'decription' => 'Decription',
            'publishTime' => 'Publish Time',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'Last Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatchshares()
    {
        return $this->hasMany(Catchshare::className(), ['groupShareId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['groupShareId' => 'id']);
    }
}
