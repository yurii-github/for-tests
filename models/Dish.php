<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dish".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $prep_time prep time in minutes
 *
 * @property User $user
 * @property DishProduct[] $dishProducts
 */
class Dish extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'prep_time'], 'integer'],
            [['created_at', 'updated_at', 'prep_time'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->getScenario() === self::SCENARIO_CREATE) {
            $this->created_at = (new \DateTime())->format('Y-m-d H:i:s');
            //$this->user_id =
        }

        $this->updated_at = (new \DateTime())->format('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }


    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['title', 'prep_time']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'prep_time' => Yii::t('app', 'Prep Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishProducts()
    {
        return $this->hasMany(DishProduct::className(), ['dish_id' => 'id']);
    }
}
