<div>
    <x-loading-indicator/>

    <style>
        .menu-tree li {
            padding: 10px 0px 0px 10px;
            border-left-color: gray;
            border-left-width: 1px;

            border-bottom-color: #cccccc;
            border-bottom-width: 1px;
            border-bottom-style: dashed;
        }

        .menu-tree ul {
            margin-bottom:-1px;
        }

        .draggable-mirror {
            background-color: yellow;
            width: 950px;
            /*
            display: flex;
            justify-content: space-between;
            */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);

        }
    </style>

    <h2>{{ $code->code }}</h2>
    {{ $code->description }}
    <hr>

    {!! xMenuTree($tree)
        ->addFirstItem(
            (new \Jiny\Html\CTag('li',true))
            ->addItem(
                // 루트등록 버튼
                xLink( xIcon($name="plus-square-dotted", $type="bootstrap")->setClass("w-4 h-4") )

                ->setAttribute('wire:click',"$"."emit('popupFormCreate','0')")
            )->addClass("py-2")
        )
        ->addClass("menu-tree")
        //->setAttribute('wire:sortable', "updateTaskOrder")
    !!}

    @push("scripts")
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    @endpush
</div>
