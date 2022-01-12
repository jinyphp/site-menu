<?php
namespace Jiny\Menu\Builder;
use \Jiny\Html\CTag;
/**
 *  Tree UL을 생성합니다.
 */
class Tree
{
    private $tree;
    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    function make()
    {
        return $this->ul($this->tree);
    }

    function ul($tree)
    {
        $ul = new CTag('ul', true);
        $_li = new CTag('li', true);
        $_a = new CTag('a',true);

        $icon_arrow = xIcon("corner-down-right")->setType("tabler-icons")->setClass("w-4 h-4 inline-block -mt-2");
        $icon_plus = xIcon($name="plus-circle-dotted", $type="bootstrap")->setClass("w-3 h-3");

        $icon_up = xIcon($name="caret-up", $type="bootstrap")->setClass("w-3 h-3 inline-block");
        $icon_down = xIcon($name="caret-down", $type="bootstrap")->setClass("w-3 h-3 inline-block");


        foreach($tree as $item) {
            $li = clone $_li;

            // drag and sortable

            if($item['level'] == 1) {
                $li->setAttribute('wire:sortable.item', $item['id']);
                $li->setAttribute('wire:key', "task-".$item['id']);
            }


            ##
            $leftBox = xDiv();
                // drag
                // 화살표
                if($item['level'] == 1) {
                    $leftBox->addItem(
                        xDiv($icon_arrow)
                        ->setAttribute('wire:sortable.handle',true)
                        ->style("cursor:move;")
                    );
                } else {
                    $leftBox->addItem(
                        $icon_arrow
                    );
                }

                // 수정링크
                $link = (clone $_a)
                    ->addItem($item['title'])
                    ->setAttribute('href', "javascript: void(0);");
                $link->setAttribute("wire:click", "$"."emit('edit','".$item['id']."')");
                $leftBox->addItem( "Id:".$item['id']."/"."pos:".$item['pos']."/" );
                $leftBox->addItem( xEnableText($item, $link) );

                // 하위 생성 버튼
                $create = (clone $_a)
                    ->addItem( $icon_plus )
                    ->setAttribute('wire:click',"$"."emit('popupFormCreate','".$item['id']."')");
                $create->addClass("px-2");
                $leftBox->addItem($create);

                $leftBox->addItem(
                    (clone $_a)
                    ->addItem( $icon_up )
                    ->setAttribute('wire:click',"sort_up('".$item['id']."')")
                    ->setAttribute('href', "javascript: void(0);")
                );
                $leftBox->addItem(
                    (clone $_a)
                    ->addItem( $icon_down )
                    ->setAttribute('wire:click',"sort_down('".$item['id']."')")
                    ->setAttribute('href', "javascript: void(0);")
                );

            $flexbox = new CTag('div', true);
            $flexbox->addClass("flex justify-between pb-1");
            $flexbox->addItem($leftBox);
            $flexbox->addItem(xDiv($item['description']));


            $li->addItem($flexbox);

            // 서브트리 재귀호출
            if(isset($item['sub'])) {
                $li->addItem($this->ul($item['sub']));
            }

            $ul->addItem($li);
        }

        return $ul;
    }


}
