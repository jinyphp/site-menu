<div>
    <x-loading-indicator/>

    <style>
        .jiny.tree ul {



            padding:0;
            margin-left:30px;
            /*
            padding:0 0 0 0;
            flex-grow: 1;
            */

            margin-top: -1px;
            margin-bottom: -1px;
        }

        .jiny.tree > ul {
            margin-left:0;
        }

        .jiny.tree li {
            /*display:flex;*/
            padding: 5px;

            border-left-color: gray;
            border-left-width: 1px;

            border-bottom-color: #cccccc;
            border-bottom-width: 1px;
            border-bottom-style: dashed;

            /*
            border-top-color: #cccccc;
            border-top-width: 1px;
            border-top-style: solid;
            */
        }

        .jiny.tree ul > li:first-child {
            /* border-bottom:0; */
        }

        .jiny.tree ul > li:last-child {
            /* border-bottom:0; */
        }

        .jiny.tree li > .title {
            padding: 5px;
            min-width:100px;
        }

        .jiny.tree .title-right {
            padding: 0 5px;
            flex-flow: 1;
        }

        .jiny.tree li > div:hover {
            background: #def2fb;
        }

        .jiny.tree .title.target {
            background: #def2fb;
        }

        .jiny.tree .btn-create {
            padding: 10px;
        }



        /* 소스 드래깅 */
        .jiny.tree li.dragging {
            background: #eeeeee;
            border: 1px solid #cccccc;
            opacity: 0.7;
        }

        .draggable-mirror {
            background-color: yellow;
            width: 950px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }
    </style>

    <h2>{{ $code->code }}</h2>
    {{ $code->description }}
    <hr>
    <form>
        @csrf
        <div class="jiny tree">
            {!! xMenuTree($tree) !!}
        </div>
    </form>



    {{-- tree drag move --}}
    <script>
        function findTagsParent(el, tag) {
            for(let i=0; i<tag.length;i++) {
                tag[i] = tag[i].toUpperCase();
            }
            let status = true;
            while(status) {
                for(i=0;i<tag.length;i++) {
                    if(el.tagName == tag[i]) status = false;
                }
                if(status == true) {
                    el = el.parentElement;
                    if(el.tagName == "BODY") break;
                }
            }
            return el;
        }



        const leafs = document.querySelectorAll('.drag-node');
        const jinyTree = document.querySelector('.jiny.tree');
        //console.log(jinyTree);
        jinyTree.setAttribute('draggable', "true");
        let dragStart = null;
        let dragTarget = null;
        let dragOver = null;
        jinyTree.addEventListener('dragstart', (e) => {
            console.log("dragstart");
            // ul은 선택할 수 없음.
            dragStart = findTagsParent(e.target, ['li']);
            if(dragStart.classList.contains('drag-node')) {
                if(dragStart) {
                    console.log(dragStart);
                    dragStart.classList.add('dragging');
                }
            } else {
                dragStart = null;
                console.log("드래그 할 수 없습니다.");
            }

        });
        jinyTree.addEventListener('dragover', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('dragenter', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('dragleave', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('drop', (e) => {
            e.preventDefault();
            console.log("drop");
            let target = findTagsParent(e.target, ['li','ul']);
            //console.log(target);
            //dragTarget = target;
            if(target.tagName == "UL") {
                // 1. ul선택
                console.log("ul은 대상이 될 수 없습니다.");
            }  else {
                // 2. li선택
                dragMoveToLi(dragStart, target);
            }

            // 서버로 정보 전송
            ajaxMenuDropSync();

        });
        jinyTree.addEventListener('dragend', (e) => {
            console.log("dragend");
            //let target = findTagsParent(e.target, 'li');

            if(dragStart && dragStart.classList.contains('dragging')) {
                dragStart.classList.remove('dragging');
            }
            //dragStart = null;
            //dragTarget = null;
        });

        function dragMoveToLi(dragStart, dragTarget) {
            if(dragStart == dragTarget) {
                console.log("자기 자신은 이동할 수 없습니다.");
                return;
            }

            if(checkDropChild(dragTarget)) {
                console.log("동일계층 하위로 이동 할 수 없습니다.")
                return;
            }


            // 드래그 노드 (이동, 맞교환, 추가동작)
            if(dragTarget.classList.contains('drag-node')) {
                console.log("Li 노드에 드래그 되었습니다.");
                dragMoveToNode(dragStart, dragTarget);
            } else
            // 추가버튼 drop (추가동작)
            if(dragTarget.classList.contains('create-sub-li')) {
                console.log("추가 버튼에 드래그 되었습니다.");
                dragMoveToCreate(dragStart, dragTarget);
            }
        }

        function dragMoveToNode(dragStart, dragTarget) {
            // 부모노드 검사
            // 부모가 같으면 노간 순서를 교환합니다.
            if(dragTarget.parentElement == dragStart.parentElement) {
                console.log("노드 순서 맞교환");
                targetNext = dragTarget.nextElementSibling;
                srcNext = dragStart.nextElementSibling;
                dragTarget.parentElement.insertBefore(dragStart, targetNext);
                dragStart.parentElement.insertBefore(dragTarget, srcNext);
            }

            // 부모 노드가 다름
            else {
                console.log("다른 노드로 이동합니다.");
                targetNext = dragTarget.nextElementSibling; // 대상 삽입위치 지정

                // 이동정보 ref, level 갑을 변경합니다.
                let parent = dragTarget.parentElement;
                dragStart.dataset.ref = parent.dataset['id']; //부모 참조값 변경
                dragStart.dataset.level = parseInt(parent.dataset['level']) + 1; // data 속성변경

                // 기존 노드를 새로운 노드로 이동합니다.
                dragTarget.parentElement.insertBefore(dragStart, targetNext);
            }
        }

        /* 생성 버튼 노트로 drop한 경우 처리*/
        function dragMoveToCreate(dragStart, dragTarget) {
            console.log("서브 노드가 추가됩니다.");
            // 동일 노드 검사
            // 예) 같은노드에서 +버튼으로 드래그하는 경
            if(dragTarget.parentElement == dragStart.parentElement) {
                console.log("동일한 부모노드 입니다. 서브등록을 취소합니다.");
            }
            // + 버튼에 drop, 여기로 이동합니다.
            else {
                // 기본적으로 버튼 li가 선택됩니다.
                // 상위 Li 찾기 li > ul > li item
                let parent = dragTarget.parentElement.parentElement;

                console.log("선택한 노드를 새로운 노드에 이동합니다.");
                dragStart.dataset.ref = parent.dataset['id'];
                dragStart.dataset.level = parseInt(parent.dataset['level']) + 1;  // data 속성변경
                dragTarget.parentElement.appendChild(dragStart);
            }
        }

        /* 이동하고자 하는 대상의 자기 자신의 자식들인지 체크함 */
        function checkDropChild(dragTarget) {
            console.log("동일계층 checking....")
            let parent = findTagsParent(dragTarget, ['li']);
            console.log(parent);

            while (parent.dataset['level'] > 0) {
                if(parent == dragStart) {
                    return true;
                } else {
                    parent = parent.parentElement;
                }
            }
            return false;
        }


        function ajaxMenuDropSync() {
            // 변경된 노드를 다시 확인
            let node = jinyTree.querySelectorAll('.jiny.tree > ul > li.drag-node');
            let ipos=0;
            let aaa=[];
            function __treepos(node) {
                let pos = [];
                node.forEach(el => {
                    console.log(el);
                    id = el.dataset['id'];
                    ipos++;
                    pos[id] = {
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'ipos':ipos,
                        'sub':null
                    };
                    aaa.push(pos[id]);

                    if(sub = el.querySelectorAll('[data-ref="'+id+'"].drag-node')) {
                        pos[id].sub = __treepos(sub);
                    }

                });

                return pos;
            }

            let sortpos = __treepos(node);
            //console.log( sortpos );
            console.log( aaa );
            //const JsonArray = JSON.stringify(aaa[0]);
            //console.log(JsonArray);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/menu/pos");

            let data = new FormData();
            let token = document.querySelector('input[name=_token]').value;
            data.append('_token', token);

            for(let i=0; i < aaa.length; i++) {
                data.append("menu[" + aaa[i].id + "][ref]", aaa[i].ref);
                data.append("menu[" + aaa[i].id + "][level]", aaa[i].level);
                data.append("menu[" + aaa[i].id + "][pos]", i+1);
            }

            xhr.onload = function() {
                var data = JSON.parse(this.responseText);
                console.log(data);
            }

            xhr.send(data);
        }

    </script>


</div>
