{{-- json 메뉴 데이터를 기반으로 tree 메뉴를 생성합니다. --}}
{{-- 데이터가 없는 경우 slot 데이터만 출력합니다. --}}
<div >
    {!! $menuTree !!}
</div>

@push('css')
<style>
    .context-menu {
        background-color: #fff;
        border:1px solid #cccccc;
        box-shadow: 1px 1px 10px rgba(0,0,0,0.1);
        padding: 0;
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

{{-- 메뉴 활성화, collapse 상태 쿠키 저장 --}}
@push('scripts')
<script>
    // 메뉴 오른쪽 마우스 클릭
    const sidebarNav = document.querySelector(".sidebar-nav");
    const menu_id = sidebarNav.dataset['code'];
    sidebarNav.addEventListener('contextmenu', function(e){
        e.preventDefault();
        console.log('sidebar click');

        let target = findTagsParent(e.target, ['li']);
        console.log(target);

        let contextMenu
        if(jiny.contextMenu) {
            console.log("존재");
            contextMenu = jiny.contextMenu;
        } else {
            console.log("생성");
            contextMenu = createSidebarContext(target.dataset['id']);
            jiny.contextMenu = contextMenu;
        }

        let wrapper = document.querySelector(".wrapper");
        wrapper.appendChild(contextMenu);

        // context Menu활성화
        contextMenu.style.display = 'block';
        contextClickPosition(e, contextMenu)





    });

    function createSidebarContext(id)
    {
        //let menu_id = 10;
        let menu = document.createElement("ul");
        menu.classList.add('context-menu');

        let li, link;
        li = document.createElement("li");
        link = document.createElement("a");
        link.innerHTML = "생성";
        link.href = "/admin/easy/menu/"+menu_id+"/items/create?ref=" + id;
        li.appendChild(link);
        menu.appendChild(li);

        li = document.createElement("li");
        link = document.createElement("a");
        link.innerHTML = "수정";
        link.href = "/admin/easy/menu/" + menu_id + '/items/' + id + "/edit";
        li.appendChild(link);

        menu.appendChild(li);
        return menu;
    }

    function contextClickPosition(e, contextMenu)
    {
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
    }


    window.addEventListener('click', function(e){
        // 배경 클릭시 contextMenu 닫기
        if(jiny.contextMenu) {
            console.log("contextMenu 제거");
            jiny.contextMenu.remove();
        }
    });



</script>

<script>

    function findTagsParent(el, tag) {
        for(let i=0; i<tag.length;i++) {
            tag[i] = tag[i].toUpperCase();
        }
        let status = true;
        //console.log("찾기....")
        //console.log(el)
        while(status) {
            for(i=0;i<tag.length;i++) {
                if(el.tagName == tag[i]) status = false;
            }
            if(status == true) {
                if(el) {
                    if(el.classList) {
                        //console.log(el);
                        if(el.classList.contains('root')) return null;
                    } else {
                        //console.log("class list가 없어요")
                    }
                } else {
                    //console.log("el 이 없어요")
                }

                el = el.parentElement;
                //sif(el.tagName == "FORM") return null;
            }
        }
        return el;
    }

    const sidebarLinks = document.querySelectorAll(".sidebar-link");
    //console.log("side-link checking");
    //console.log(sidebarLinks);


    let collapse = JSON.parse( getCookie("__menu_collapse") );
    //console.log(collapse)
    function _menu_collapse(target) {
        let id = target.dataset['id'];
        //console.log("collapse 설정 변경");
        //console.log(target);
        if(target.classList.contains("submenu")) {
            //console.log("collapse menu");
            //console.log(collapse)
            if(collapse) {

            } else {
                collapse = [];
            }

            // 기존에 선택된 값이 있는경우, 삭제합니다.
            for(let i=0; i<collapse.length; i++) {
                if(collapse[i].id == id) {
                    //console.log("collapse 삭제");
                    collapse.splice(i, 1);
                    //console.log(collapse)
                    // 쿠키를 변경합니다.
                    setCookie("__menu_collapse", JSON.stringify(collapse), 36000);
                    return;
                }
            }

            // 펼침 선택
            collapse.push({'id': target.dataset['id']});
            // console.log(collapse)
            // 쿠기를 추가합니다.
            setCookie("__menu_collapse", JSON.stringify(collapse), 36000);
            //console.log("collapse 저장");
        }
    }

    let active = JSON.parse( getCookie("__menu_active") );
    function _menu_active(target) {
        active = {
            'id': target.dataset['id']
        };
        setCookie("__menu_active", JSON.stringify(active), 36000);
    }

    // 링크클릭
    sidebarLinks.forEach(el => {
        el.addEventListener('click', function(e){
            console.log("sidebar-link click");
            console.log(e.target);
            // sidebar-item 찾기
            let target = e.target;
            while(!target.classList.contains('sidebar-item')) {
                target = target.parentElement;
            }

            // 링크검사
            let link = target.querySelector('a.sidebar-link');
            //console.log(link);
            if(link) {
                if(link.href && link.href != "javascript:void(0)") {
                    // 링크이동 허용
                } else {
                    e.preventDefault(); // 페이지 이동막기
                }
            }


            // 메뉴 collapse 쿠키 상태 저장
            _menu_collapse(target);

            // 메뉴 active 선택
            _menu_active(target);
        });
    });

    let _collapse = getCookie("__menu_collapse");
    console.log("__menu_collapse = " + _collapse );

    let _active = getCookie("__menu_active");
    console.log("__menu_active = " + _active );

    // 쿠키 생성 함수
    function setCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

</script>
@endpush

