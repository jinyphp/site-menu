<div>

    <x-link wire:click="popupNew">new</x-link>

    {{-- 테이블 출력--}}
    <x-table>
        <x-thead>
            <tr>
                <th class="w-8">Id</th>
                <th>Code</th>
                <th>Items</th>
                <th>Description</th>
                <th class="w-48">regdate</th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($menus as $item)
                <tr>
                    <td class="w-8">{{$item->id}}</td>
                    <td><a href="#" wire:click="popupEdit({{$item['id']}})">{{$item->code}}</a></td>
                    <td><a href="/admin/site/menu/items">Items</a></td>
                    <td>{{$item->description}}</td>
                    <td class="w-48">{{$item->created_at}}</td>
                </tr>
            @endforeach
        </tbody>
    </x-table>

    <x-jet-dialog-modal wire:model="popup">
        <x-slot name="title">
            {{ __('메뉴코드') }}
        </x-slot>

        <x-slot name="content">
            <div>
                <div>코드</div>
                <div>
                    <input type="text" wire:model="form.code">
                </div>
            </div>

            <div>
                <div>설명</div>
                <div>
                    <input type="text" wire:model="form.description">
                </div>
            </div>


        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="popupClose" wire:loading.attr="disabled">
                {{ __('취소') }}
            </x-jet-secondary-button>

            @if (isset($form['id']))
                <x-jet-danger-button class="ml-2" wire:click="popupEditSubmit" wire:loading.attr="disabled">
                    {{ __('수정') }}
                </x-jet-danger-button>
            @else
            <x-jet-danger-button class="ml-2" wire:click="popupNewSubmit" wire:loading.attr="disabled">
                {{ __('등록') }}
            </x-jet-danger-button>
            @endif

        </x-slot>
    </x-jet-dialog-modal>

</div>
