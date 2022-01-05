{{-- json 메뉴 데이터를 기반으로 tree 메뉴를 생성합니다. --}}
{{-- 데이터가 없는 경우 slot 데이터만 출력합니다. --}}
{!! $builder($slot) !!}


<script>
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










    // 쿠키 생성 함수
    function setCookie(key, value, day){
        var expire = new Date();
        expire.setDate(expire.getDate() + day);
        cookies = key + '=' + escape(value) + '; path=/ '; // 한글 깨짐을 막기위해 escape(value)를 합니다.
        if(typeof day != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
        document.cookie = cookies;
    }

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


</script>


