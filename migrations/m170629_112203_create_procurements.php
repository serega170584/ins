<?php

use yii\db\Migration;

class m170629_112203_create_procurements extends Migration
{
    public function safeUp()
    {
        $this->insert('sss_node_type', [
            'name' => 'Закупки 123',
            'code' => 'about_proc'
        ]);

        $this->insert('sss_node_type', [
            'name' => 'Закупки 456',
            'code' => 'about_ui'
        ]);

        $this->insert('sss_node_type', [
            'name' => 'Закупки 789',
            'code' => 'about_ras'
        ]);

    }

    public function safeDown()
    {
        $this->delete('sbl_node_type', 'code = "about_proc"');
        $this->delete('sbl_node_type', 'code = "about_ui"');
        $this->delete('sbl_node_type', 'code = "about_ras"');
    }
}
