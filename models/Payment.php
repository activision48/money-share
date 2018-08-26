<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property string $paidDate
 * @property double $paidValue
 * @property integer $memberId
 * @property integer $groupShareId
 * @property double $exten
 * @property string $createTime
 * @property string $lastUpdateTime
 * @property integer $is_win
 *
 * @property Groupshare $groupShare
 * @property Member $member
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paidDate', 'createTime', 'lastUpdateTime'], 'safe'],
            [['paidValue', 'exten'], 'number'],
            [['memberId', 'groupShareId', 'is_win'], 'integer'],
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
            'paidDate' => 'Paid Date',
            'paidValue' => 'Paid Value',
            'memberId' => 'Member ID',
            'groupShareId' => 'Group Share ID',
            'exten' => 'Exten',
            'createTime' => 'Create Time',
            'lastUpdateTime' => 'Last Update Time',
            'is_win' => 'Is Win',
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
