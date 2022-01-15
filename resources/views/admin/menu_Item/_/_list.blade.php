<x-datatable>
    <x-data-table-thead>
        <th width='50'>Id</th>
        <th width='200'> {!! xWireLink('code', "orderBy('code')") !!}</th>
        <th width='100'>설정</th>
        <th>설명</th>
        <th width='200'>생성일자</th>
    </x-data-table-thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)
        <x-data-table-tr :item="$item" :selected="$selected">
            <td width='50'>{{$item->id}}</td>
            <td width='200'>
                {!! $popupEdit($item, $item->code) !!}
            </td>
            <td width='100'><a href="/admin/site/menu/{{$item->id}}">item</a></td>
            <td>{{$item->description}}</td>
            <td width='200'>{{$item->created_at}}</td>
        </x-data-table-tr>
        @endforeach

    </tbody>
    @endif
</x-datatable>


@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


