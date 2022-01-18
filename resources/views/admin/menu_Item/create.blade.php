<form action="{{route($actions['routename'].".store",['10','941'])}}" method="POST">
    @csrf
    @include("jinymenu::admin.menu_item.form")
</form>

