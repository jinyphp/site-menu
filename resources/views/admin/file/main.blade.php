{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme theme="admin.sidebar">
    <x-theme-layout>

        <!-- Module Title Bar -->
        @if(Module::has('Titlebar'))
            @livewire('TitleBar', ['actions'=>$actions])
        @endif
        <!-- end -->



        <style>
            .directory ul {
                padding-left: 15px;
            }

            .directory li {
                padding: 10px 0px 0px 10px;
                border-left-color: gray;
                border-left-width: 1px;
                margin-top: -1px;
                border-top-color: #cccccc;
                border-top-width: 1px;
                border-top-style: dashed;
            }
        </style>


        <x-card>
            <x-card-header>
                <ul class="p-0 m-0">
                    <li class="float-left px-2">
                        <a href="/admin/site/menus">Menu Code</a>
                    </li>
                    <li class="float-left px-2">
                        <a href="/admin/site/menu/file">Json Files</a>
                    </li>
                </ul>
            </x-card-header>
            <x-card-body>

                @livewire('FileExplore', [
                    'actions' => $actions,
                    'path' => '/resources/menus'
                ])

            </x-card-body>
        </x-card>


        <!-- dropzone -->
        @include("jinyfile::script.drop")



        @include('jinytable::setActionRule')

    </x-theme-layout>
</x-theme>
