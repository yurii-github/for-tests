<?php

use yii\db\Migration;


class m180313_172858_add_dishes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //   название блюда, время приготовления блюда в минутах, набор продуктов, из которых состоит блюдо

        $this->createTable('product', [
            'id' => $this->bigPrimaryKey(),
            'title' => $this->string(255),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('dish', [
            'id' => $this->bigPrimaryKey(),
            'title' => $this->string(255),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'prep_time' => $this->integer()->unsigned()->comment('prep time in minutes'),
        ]);

        $this->addForeignKey('FK_'.'product'.'_user',
            'product', 'user_id',
            'user', 'id',
            'RESTRICT', 'CASCADE');

        $this->addForeignKey('FK_'.'dish'.'_user',
            'dish', 'user_id',
            'user', 'id',
            'RESTRICT', 'CASCADE');

        $this->createTable('dish_product', [
            'dish_id' => $this->bigInteger(),
            'product_id' => $this->bigInteger()
        ]);

        $this->addForeignKey('FK_'.'dish_product'.'_product',
            'dish_product', 'product_id',
            'product', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('FK_'.'dish_product'.'_dish',
            'dish_product', 'dish_id',
            'dish', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dish_product');
        $this->dropTable('dish');
        $this->dropTable('product');
    }
}
