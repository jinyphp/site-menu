{{-- json 메뉴 데이터를 기반으로 tree 메뉴를 생성합니다. --}}
{{-- 데이터가 없는 경우 slot 데이터만 출력합니다. --}}
{!! $builder($slot) !!}


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
        //let id = target.dataset['id'];
        active = {
            'id': target.dataset['id']
        };

        setCookie("__menu_active", JSON.stringify(active), 36000);

        /*
            if(active) {

            } else {
                active = [];
            }

            // 기존에 선택된 값이 있는경우, 삭제합니다.
            for(let i=0; i<active.length; i++) {
                if(active[i].id == id) {
                    //console.log("active 삭제");
                    active.splice(i, 1);
                    //console.log(active)
                    // 쿠키를 변경합니다.
                    setCookie("__menu_active", JSON.stringify(active), 36000);
                    return;
                }
            }

            // 펼침 선택
            active.push({'id': target.dataset['id']});
            // console.log(active)
            // 쿠기를 추가합니다.
            setCookie("__menu_active", JSON.stringify(active), 36000);
            //console.log("active 저장");
        //}
        */
    }



    sidebarLinks.forEach(el => {
        el.addEventListener('click', function(e){
            e.preventDefault();
            console.log("click");
            //console.log(e.target);
            let target = findTagsParent(e.target, ['li']);

            id = target.dataset['id'];
            console.log(target);

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





    /*
    window.addEventListener('load',function(){
        //console.log("menu load");
        // 메뉴 활성화 정보
        let menu = getCookie("menu");
        if(!menu) {
            menu=[];

        } else {
            menu = JSON.parse(menu);
        }
        console.log(menu);
        let active = document.querySelector("a[data-menu='" + menu +"']");
        active.parentNode.classList.add("active");
        //console.log(document.querySelector("a[data-menu='" + menu +"']"));

        // 메뉴검사 및 이벤트 설정
        let menu_gnb_nav = document.querySelectorAll(".sidebar-nav a");
        menu_gnb_nav.forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();


                let mid = e.target.getAttribute('data-menu');
                //alert(mid);
                setCookie("menu", mid, 1); //1일
                //console.log(e.target.getAttribute('data-menu'));


                // Active Class 설정
                menu_active_clear();
                let target = e.target;
                target = menu_active(target);
                while(target.parentNode.parentNode.tagName == "LI") {
                    //console.log(target.parentNode.parentNode);
                    target = target.parentNode.parentNode;
                    target.classList.add("active");
                }

                // 링크이동
                let link = e.target;
                while (link.tagName != "A") {
                    link = link.parentNode;
                    if (link.tagName == "LI") break; // 무한반복 오류를 방지하기 위하여, li의 경우 탈출
                }

                if(link.href) {
                    // 현재위치와 다른 링크 클릭시 이동
                    if(window.location.href != link.href) {
                        window.location.href = link.href;
                    }
                } else {
                    alert(link.tagName);
                }




            });

        });

        function menu_active_clear()
        {
            menu_gnb_nav.forEach(el => {
                //console.log(el.parentNode);
                el.parentNode.classList.remove("active");
            });
        }

        function menu_active(target) {
            while(target.tagName != "A") {
                target = target.parentNode;
            }
            target.parentNode.classList.add("active");
            return target.parentNode;
        }

    });
    */










    // 쿠키 생성 함수
    /*
    function setCookie(key, value, day){
        var expire = new Date();
        expire.setDate(expire.getDate() + day);
        cookies = key + '=' + escape(value) + '; path=/ '; // 한글 깨짐을 막기위해 escape(value)를 합니다.
        //cookies = key + '=' + value + '; path=/ ';
        if(typeof day != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
        document.cookie = cookies;
    }
    */

    function setCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    /*
    function getCookie(key) {
        var result = null;
        var cookie = document.cookie.split(';');
        cookie.some(function (item) {
            // 공백을 제거
            item = item.replace(' ', '');

            var dic = item.split('=');

            if (key === dic[0]) {
                result = dic[1];
                return true;    // break;
            }
        });
        return result;
    }
    */
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


