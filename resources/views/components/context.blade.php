
@push('css')
<style>
    .context-menu {
        background-color: #fff;
        border:1px solid #cccccc;
        box-shadow: 1px 1px 10px rgba(0,0,0,0.1);
        padding: 10px 0;
        width:200px;
        position: fixed;
        z-index: 10000;
        left:0;
        top:0;
        display: none;
    }

    .context-menu li {
        cursor: pointer;
        padding:8px 15px;
    }
    .context-menu li:hover {
        background-color: #f8f8f8;
    }
    .context-menu .divider {
        border-bottom: 1px solid #eeeeee;
        margin:10px 0;
    }
</style>
@endpush

<ul class="context-menu">
    {{$slot}}
</ul>

@push('scripts')
<script>
    // 오른쪽 마우스 클릭
    window.addEventListener('contextmenu', function(e){
        //console.log('right click');
        e.preventDefault();

        // context Menu활성화
        var contextMenu = document.querySelector('.context-menu');
        contextMenu.style.display = 'block';

        // top위치 구하기
        var top;
        if((e.clientY + contextMenu.offsetHeight)  > window.innerHeight) {
            top = window.innerHeight - contextMenu.offsetHeight ;
        } else {
            top = e.clientY;
        }
        contextMenu.style.top =  top + "px";

        // left 위치 구하기
        var left;
        if((e.clientX + contextMenu.offsetWidth) > window.innerWidth) {
            left = window.innerWidth - contextMenu.offsetWidth ;
        } else {
            left = e.clientX;
        }
        contextMenu.style.left = left + 'px';
    });

    window.addEventListener('click', function(e){
        // 배경 클릭시 contextMenu 닫기
        var contextMenu = document.querySelector('.context-menu');
        contextMenu.style.display = 'none';
    });
</script>

@endpush
