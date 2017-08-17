<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class m140618_045255_create_settings extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%settings}}',
            [
                'id' => $this->primaryKey(),
                'type' => $this->string(255)->notNull(),
                'section' => $this->string(255)->notNull(),
                'key' => $this->string(255)->notNull(),
                'value' => $this->text(),
                'active' => $this->boolean(),
                'created' => $this->dateTime(),
                'modified' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');
    }
}
