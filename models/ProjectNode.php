<?php

namespace app\models;

use Yii;

class Item //extends \yii\base\Model
{
    public $label = '';
    public $url = '#';
    public $items = [];

    public static function toArray($item)
    {
        $array = ['label' => $item->label, 'url' => $item->url ];
        if( count($item->items) )
        {
            $items = [];
            foreach ($item->items as $i){
                $items[] = self::toArray($i);
            }
            $array['items'] = $items;
        }
        return $array;
    }
}

class ProjectNode extends \kartik\tree\models\Tree
{
    
    private $_projectInfo = null;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mh_project_tree';
    }   
    
    /**
     * Override isDisabled method if you need as shown in the
     * example below. You can override similarly other methods
     * like isActive, isMovable etc.
     */
    /*
    public function isDisabled()
    {
        if (Yii::$app->user->id !== 'admin') {
            return true;
        }
        return parent::isDisabled();
    }
    */    
        
    public function getProjectInfo()
    {
        if ($this->_projectInfo === null){
            return $this->hasOne(ProjectInfo::className(), ['id' => 'id']);
        }
        return $this->_projectInfo;
    }
    
    public function load($data, $formName = null, $loadInfo = true)
    {       
        $result = parent::load($data, $formName);
        if (!$result){
            return $result;
        }
        
        if ($loadInfo) {
            $tmpInfo = $this->projectInfo; //not "_projectInfo"
            if ( $tmpInfo === null ) {
                $tmpInfo = new ProjectInfo();
            }

            if( $tmpInfo->load($data, $formName) ) {
                $tmpInfo->id = $this->id;
                $tmpInfo->name = $this->name;
                $this->_projectInfo = $tmpInfo;
            }
        }
        
        return $result;
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        $result = parent::save($runValidation, $attributeNames);
        if (!$result){
            return $result;
        }   
             
        if ($this->_projectInfo !== null) {
            $this->_projectInfo->id = $this->id;
            $this->_projectInfo->name = $this->name;
            $result = $this->_projectInfo->save($runValidation, $attributeNames);
        }
        
        return $result;
    }
        
    public function renderChildItems()
    {
        $stack = [];
        $curr = $root = new Item();       
        $nodeDepth = $currDepth = $this->lvl;
        
        $nodes = $this->children()->all();
        foreach ( $nodes as $node) {
            if (!$node->isVisible() || !$node->isActive()) {
                continue;
            }
            $nodeDepth = $node->lvl;
            $nodeLeft = $node->lft;
            $nodeName = $node->name;
            
            if ($nodeDepth > $currDepth) {
                for ($i = $nodeDepth - $currDepth; $i > 0; $i-- ){
                    $stack[] = $curr;
                    
                    if (empty($curr->items)){
                        $curr->items = new Item();
                    }
                    $curr = $curr->items[count($curr->items)-1];
                }

                $currDepth = $currDepth + ($nodeDepth - $currDepth);
            } elseif ($nodeDepth < $currDepth) {
                for ($i = $currDepth - $nodeDepth; $i > 0; $i-- ){
                    $curr = array_pop($stack);
                }
                $currDepth = $currDepth - ($currDepth - $nodeDepth);
            }

            $item = new Item(['label' => $nodeName]);
            $item->label = $nodeName;
            $curr->items[] = $item;

        }        
        
        if (count($root->items) > 0){
            return Item::toArray($root)['items'];
        }
        return [];        
    }
    
    
    public static function getAllItems()
    {
        $stack = [];
        $curr = $root = new Item();
        $nodeDepth = $currDepth = 0;
        
        $nodes = self::find()->addOrderBy('root, lft')->all();
        foreach ( $nodes as $node) {
            if (!$node->isVisible() || !$node->isActive()) {
                continue;
            }
            $nodeDepth = $node->lvl;
            $nodeLeft = $node->lft;
            $nodeName = $node->name;
        
            if ($nodeDepth > $currDepth) {
                for ($i = $nodeDepth - $currDepth; $i > 0; $i-- ){
                    $stack[] = $curr;
        
                    if (empty($curr->items)){
                        $curr->items = new Item();
                    }
                    $curr = $curr->items[count($curr->items)-1];
                }
        
                $currDepth = $currDepth + ($nodeDepth - $currDepth);
            } elseif ($nodeDepth < $currDepth) {
                for ($i = $currDepth - $nodeDepth; $i > 0; $i-- ){
                    $curr = array_pop($stack);
                }
                $currDepth = $currDepth - ($currDepth - $nodeDepth);
            }
        
            $item = new Item(['label' => $nodeName]);
            $item->label = $nodeName;
            $curr->items[] = $item;
            
            //echo "<br>root:<br>";
            //print_r($root);
        }
        
        if (count($root->items) > 0){
            return Item::toArray($root)['items'];
        }
        return [];
    }
    
    
    public function addItems($items)
    {
        $count = 0;
        foreach ($items as $item){
            $child = new ProjectNode();
            $child->load($item, '', false);
            if ($child->appendTo($this)){
                $count++;
            }
            
            if (isset($item['items'])){
                $count += $child->addItems($item['items']);
            }
        }
        return $count;
    } 
    
    public function rootInitDefTree()
    {
        $items = [
            [
                'name' => 'exterior', 
                'items' =>  [
                    ['name' => 'main body'],
                    ['name' => 'front bumper'],
                    ['name' => 'rear bumper'],
                    ['name' => 'grill '],
                    ['name' => 'chrome'],
                    ['name' => 'head lamp'],
                    ['name' => 'tail lamp'],
                    ['name' => 'roof rack'],
                    ['name' => 'cowl panel'],
                    ['name' => 'fog lamp'],
                    ['name' => 'door mirror'],
                    ['name' => 'door handle'],
                    ['name' => 'wheel'],
                    ['name' => 'others'],
                ],
            ],

            [
                'name'  => 'interior',
                'items' => [
                    ['name' => 'IP'],
                    ['name' => 'cluster'],
                    ['name' => 'console'],
                    ['name' => 'gear shifter'],
                    ['name' => 'brake knob'],
                    ['name' => 'door trim'],
                    ['name' => 'seats'],
                    ['name' => 'STG wheel'],
                    ['name' => 'reading lamp'],
                    ['name' => 'inner mirror'],
                    ['name' => 'roof '],
                    ['name' => 'ABC pillar'],
                    ['name' => 'sun visor'],
                    ['name' => 'others'],                
                ],
            ],
        ];
        
        return $this->addItems($items);
    }
}


?>