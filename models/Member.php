<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $nickname
 * @property integer $status
 * @property string $createTime
 * @property string $lastUpdateTime
 *
 * @property Catchshare[] $catchshares
 * @property Payment[] $payments
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['createTime', 'lastUpdateTime'], 'safe'],
            [['firstname', 'lastname', 'nickname'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'nickname' => 'Nickname',
            'status' => 'Status',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'Last Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatchshares()
    {
        return $this->hasMany(Catchshare::className(), ['memberId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['memberId' => 'id']);
    }
    
    public function getDisplay(){
    	return $this->nickname;
    }
}
