<div>
    <x-loading-indicator/>

    <style>
        .menu-tree li {
            padding: 10px 0px 0px 10px;
            border-left-color: gray;
            border-left-width: 1px;

            border-top-color: #cccccc;
            border-top-width: 1px;
            border-top-style: dashed;
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



    {!! xMenuTree($tree)->addFirstItem(
            xLink( xIcon($name="plus-square-dotted", $type="bootstrap")->setClass("w-4 h-4") )
            ->setAttribute('wire:click',"$"."emit('popupFormCreate','0')")
        )
        ->addClass("menu-tree")
        ->setAttribute('wire:sortable', "updateTaskOrder")
    !!}

    @push("scripts")
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    @endpush
</div>
