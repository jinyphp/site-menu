{{-- json 메뉴 데이터를 기반으로 tree 메뉴를 생성합니다. --}}
{{-- 데이터가 없는 경우 slot 데이터만 출력합니다. --}}
{!! $builder($slot) !!}

{{-- 메뉴 활성화, collapse 상태 쿠키 저장 --}}
@push('scripts')
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

        if(target.classList.contains("menu-collapse")) {
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

    sidebarLinks.forEach(el => {
        el.addEventListener('click', function(e){
            //e.preventDefault();
            console.log("click");
            //console.log(e.target);
            let target = findTagsParent(e.target, ['li']);

            id = target.dataset['id'];
            //console.log(target);

            // 메뉴 collapse 쿠키 상태 저장
            _menu_collapse(target);

            // 메뉴 active 선택
            _menu_active(target);
        });
    });

    //let _collapse = getCookie("__menu_collapse");
    //console.log("__menu_collapse = " + _collapse );

    //let _active = getCookie("__menu_active");
    //console.log("__menu_active = " + _active );

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

