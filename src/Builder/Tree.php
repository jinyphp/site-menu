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

    public function make()
    {
        //dd($this->tree);
        return $this->ul($this->tree);
    }

    private function ul($tree)
    {
        $ul = new CTag('ul', true);
        foreach($tree as $item) {
            $li = $this->li($item);
            $ul->addItem($li);
        }

        $dropzone = xDiv();
        //$dropzone->addItem("d");
        $dropzone->addClass("dropzone");
        $ul->addItem($dropzone);

        //
        //$ul->setAttribute('data-id',$item['id']);
        $ul->setAttribute('draggable',"true");
        $ul->addClass('drag-node');

        return $ul;
    }


    private function li($item)
    {
        $li = new CTag('li', true);
        $li->setAttribute('data-id', $item['id']);
        $li->setAttribute('data-level', $item['level']);
        $li->setAttribute('data-ref', $item['ref']);
        $li->setAttribute('data-pos', $item['pos']);
        $li->setAttribute('draggable', "true");
        $li->addClass('drag-node');

        $content = $this->content($item);
        $li->addItem($content);

        // 서브트리 재귀호출
        if(isset($item['sub'])) {
            $li->addItem($this->ul($item['sub']));
        } else {
            // subzone을 위한 빈 ul 등록
            /*
            $ul = new CTag('ul', true);

            $subzone = xDiv();
            $subzone->addItem("sub");
            $subzone->addClass("subzone hidden");
            $ul->addItem($subzone);

            $ul->setAttribute('draggable',"true");
            $ul->addClass('drag-node');

            $li->addItem($ul);
            */
        }

        return $li;
    }

    private function content($item)
    {
        $_a = new CTag('a',true);


        $icon_plus = xIcon($name="plus-circle-dotted", $type="bootstrap")->setClass("w-3 h-3");

        $icon_up = xIcon($name="caret-up", $type="bootstrap")->setClass("w-3 h-3 inline-block");
        $icon_down = xIcon($name="caret-down", $type="bootstrap")->setClass("w-3 h-3 inline-block");


        ##
        $leftBox = xDiv();

        //$icon_arrow = xIcon("corner-down-right")->setType("tabler-icons")->setClass("w-4 h-4 inline-block -mt-2");
        //$leftBox->addItem($icon_arrow);

        // 위치정보
        $leftBox->addItem( "Id:".$item['id']."/"."pos:".$item['pos']."/" );


        // 메뉴 수정링크
        $link = (clone $_a)
            ->addItem($item['title'])
            ->setAttribute('href', "javascript: void(0);");
        $link->setAttribute("wire:click", "$"."emit('edit','".$item['id']."')");
        $leftBox->addItem( xEnableText($item, $link) );


        // 하위 생성 버튼
        $create = (clone $_a)
            ->addItem( $icon_plus )
            ->setAttribute('wire:click',"$"."emit('popupFormCreate','".$item['id']."')");
        $create->addClass("px-2");
        $leftBox->addItem($create);


        // 상위이동
        $leftBox->addItem(
            (clone $_a)
            ->addItem( $icon_up )
            ->setAttribute('wire:click',"move_up('".$item['id']."')")
            ->setAttribute('href', "javascript: void(0);")
        );

        // 하위이동
        $leftBox->addItem(
            (clone $_a)
            ->addItem( $icon_down )
            ->setAttribute('wire:click',"move_down('".$item['id']."')")
            ->setAttribute('href', "javascript: void(0);")
        );


        // flex box로 출력
        $flexbox = new CTag('div', true);
        $flexbox->addClass("flex justify-between pb-1");
        $flexbox->addItem($leftBox);
        $flexbox->addItem(xDiv($item['description']));


        return $flexbox;
    }

}
