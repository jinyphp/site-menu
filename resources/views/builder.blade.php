<div>
    {!! $this->build() !!}

    {{--
    <script>
        let menu_gnb_nav = document.querySelectorAll(".sidebar-nav a");

        function menu_active_clear()
        {
            menu_gnb_nav.forEach(el => {
                //console.log(el.parentNode);
                el.parentNode.classList.remove("active");
            });
        }

        menu_gnb_nav.forEach(el => {

            el.addEventListener('click', e => {
                e.preventDefault();

                /*
                //let mid = e.target.getAttribute('data-menu');
                //setCookie("menu_gnb", mid, 1); //1일
                //console.log(e.target.getAttribute('data-menu'));
                */

                menu_active_clear();
                let target = e.target;
                target = menu_active(target);
                while(target.parentNode.parentNode.tagName == "LI") {
                    //console.log(target.parentNode.parentNode);
                    target = target.parentNode.parentNode;
                    target.classList.add("active");
                }


            });
        });


        function menu_active(target) {
            while(target.tagName != "A") {
                target = target.parentNode;
            }
            target.parentNode.classList.add("active");
            return target.parentNode;
        }

        // 쿠키 생성 함수
        function setCookie(cName, cValue, cDay){
            var expire = new Date();
            expire.setDate(expire.getDate() + cDay);
            cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
            if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
            document.cookie = cookies;
        }

    </script>
    --}}
</div>
