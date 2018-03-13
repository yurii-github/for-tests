<?php

namespace app\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

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
 *
 * @property Product[] $products
 */
class Dish extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_SEARCH = 'search';

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
            [['user_id', 'prep_time', 'created_at', 'updated_at', 'products'], 'safe', 'on' => self::SCENARIO_SEARCH],
            [['user_id', 'prep_time'], 'integer'],
            [['created_at', 'updated_at', 'prep_time'], 'required', 'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_CREATE]],
            [['created_at', 'updated_at', 'products'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function setProducts($value)
    {
        if (is_array($value)) {
            $ids = [];
            foreach ($value as $item) {
                if ($item instanceof Product) {
                    $ids[] = $item->id;
                } else {
                    $ids[] = $item;
                }
            }
            $products = Product::find()->where(['id' => $ids])->all();
            $this->products = $products;
        }
    }

    public function beforeSave($insert)
    {
        if ($this->getScenario() === self::SCENARIO_SEARCH) {
            throw new Exception('cannot save in search mode');
        }

        if ($this->getScenario() === self::SCENARIO_CREATE) {
            $this->created_at = (new \DateTime())->format('Y-m-d H:i:s');
            //$this->user_id =
        }
        $this->updated_at = (new \DateTime())->format('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->addProducts($this->products);
        return parent::afterSave($insert, $changedAttributes);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['title', 'prep_time', 'products'],
            self::SCENARIO_CREATE => ['title', 'prep_time', 'products', 'created_at', 'updated_at']
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

    public function addProducts(array $products = [])
    {
        $this->unlinkAll('products', true);

        foreach ($products as $product) {
            if (!($product instanceof Product)) {
                throw new InvalidArgumentException('not product model');
            }
            $this->link('products', $product);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->via('dishProducts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishProducts()
    {
        return $this->hasMany(DishProduct::className(), ['dish_id' => 'id']);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Dish::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $ids = ArrayHelper::getColumn($this->products, 'id');

        foreach ($ids as $id) {
            $query->andWhere(['id' => $id]);
        }

        return $dataProvider;
    }
}
