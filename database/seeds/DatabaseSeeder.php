<?php

use App\Models\TreeNode;
use App\Models\TreeNodeName;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\DB::table('tree_nodes')->count() < 1) {
            try {
                \DB::beginTransaction();
                foreach ($this->treeNodesToSeed as $item) {
                    $itName = $item['it_name'];
                    $enName = $item['en_name'];
                    unset($item['it_name']);
                    unset($item['en_name']);
                    TreeNode::query()->create($item);
                    TreeNodeName::query()->create([
                        'node_name' => $itName, 'language' => 'italian', 'tree_node_id' => $item['node_id']
                    ]);
                    TreeNodeName::query()->create([
                        'node_name' => $enName, 'language' => 'english', 'tree_node_id' => $item['node_id']
                    ]);
                }
                \DB::commit();
            } catch (\Exception $exception) {
                \DB::rollback();
                throw $exception;
            }

        }
    }

    private $treeNodesToSeed = [
        ['node_id' => 1, 'level' => 2, 'i_left' => 2, 'i_right' => 3, 'it_name' => 'Marketing', 'en_name' => 'Marketing'],
        ['node_id' => 2, 'level' => 2, 'i_left' => 4, 'i_right' => 5, 'it_name' => 'Supporto tecnico', 'en_name' => 'Helpdesk'],
        ['node_id' => 3, 'level' => 2, 'i_left' => 6, 'i_right' => 7, 'it_name' => 'Managers', 'en_name' => 'Managers'],
        ['node_id' => 4, 'level' => 2, 'i_left' => 8, 'i_right' => 9, 'it_name' => 'Assistenza cliente', 'en_name' => 'Customer account'],
        ['node_id' => 5, 'level' => 1, 'i_left' => 1, 'i_right' => 24, 'it_name' => 'Docebo', 'en_name' => 'Docebo'],
        ['node_id' => 6, 'level' => 2, 'i_left' => 10, 'i_right' => 11, 'it_name' => 'Amministrazione', 'en_name' => 'Accounting'],
        ['node_id' => 7, 'level' => 2, 'i_left' => 12, 'i_right' => 19, 'it_name' => 'Supporto vendite', 'en_name' => 'Sales'],
        ['node_id' => 8, 'level' => 3, 'i_left' => 15, 'i_right' => 16, 'it_name' => 'Italia', 'en_name' => 'Italy'],
        ['node_id' => 9, 'level' => 3, 'i_left' => 17, 'i_right' => 18, 'it_name' => 'Europa', 'en_name' => 'Europe'],
        ['node_id' => 10, 'level' => 2, 'i_left' => 20, 'i_right' => 21, 'it_name' => 'Sviluppatori', 'en_name' => 'Developers'],
        ['node_id' => 11, 'level' => 3, 'i_left' => 13, 'i_right' => 14, 'it_name' => 'Nord America', 'en_name' => 'North America'],
        ['node_id' => 12, 'level' => 2, 'i_left' => 22, 'i_right' => 23, 'it_name' => 'Controllo Qualità', 'en_name' => 'Quality Assurance']
    ];
}
