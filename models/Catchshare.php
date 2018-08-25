<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catchshare".
 *
 * @property integer $id
 * @property integer $memberId
 * @property integer $groupShareId
 * @property integer $amount
 *
 * @property Groupshare $groupShare
 * @property Member $member
 */
class Catchshare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catchshare';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['memberId', 'groupShareId', 'amount'], 'integer'],
            [['groupShareId'], 'exist', 'skipOnError' => true, 'targetClass' => Groupshare::className(), 'targetAttribute' => ['groupShareId' => 'id']],
            [['memberId'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['memberId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memberId' => 'Member ID',
            'groupShareId' => 'Group Share ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupShare()
    {
        return $this->hasOne(Groupshare::className(), ['id' => 'groupShareId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'memberId']);
    }
}
