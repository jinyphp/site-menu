
<form action="{{route($actions['routename'].".store")}}" method="POST">
    @csrf

    @include("jinymenu::admin.modal.menu_code.form")
    <input type="submit" value="submit">
</form>

<script>

</script>
