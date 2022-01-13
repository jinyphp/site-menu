<div>
    <x-loading-indicator/>

    <style>
        .menu-tree li {
            padding: 10px 0px 0px 10px;
            border-left-color: gray;
            border-left-width: 1px;

            border-bottom-color: #cccccc;
            border-bottom-width: 1px;
            border-bottom-style: dashed;
        }

        .jiny.tree ul {
            position: relative;
        }

        .jiny.tree .dropzone {
            position: absolute;
            background: #eeeeee;
            top:0;
            left:0;
            width:32px;
            height: 100%;

        }
        .jiny.tree .dropzone:hover {
            background: #cccccc;
        }

        .jiny.tree li:hover .subzone {
            background: #eeeeee;
            height: 30px;
            display:block;
        }

        .jiny.tree li.dragging {
            background: #eeeeee;
            opacity: 0.5;
        }
        .menu-tree ul {
            margin-bottom:-1px;

        }



        .draggable-mirror {
            background-color: yellow;
            width: 950px;
            /*
            display: flex;
            justify-content: space-between;
            */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);

        }
    </style>

    <h2>{{ $code->code }}</h2>
    {{ $code->description }}
    <hr>
    <form>
        @csrf
        <div class="jiny tree">
            {!! xMenuTree($tree)
                ->addFirstItem(
                    (new \Jiny\Html\CTag('li',true))
                    ->addItem(
                        // 루트등록 버튼
                        xLink( xIcon($name="plus-square-dotted", $type="bootstrap")->setClass("w-4 h-4") )

                        ->setAttribute('wire:click',"$"."emit('popupFormCreate','0')")
                    )->addClass("py-2")
                )
                ->addClass("menu-tree")
                //->setAttribute('wire:sortable', "updateTaskOrder")
            !!}
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
        jinyTree.addEventListener('dragstart', (e) => {
            console.log("dragstart");
            dragStart = findTagsParent(e.target, ['li','ul']);
            dragStart.classList.add('dragging');
            console.log(dragStart);
            //console.log(e.target);
        });
        jinyTree.addEventListener('dragover', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('drop', (e) => {
            e.preventDefault();
            console.log("drop");
            let target = findTagsParent(e.target, ['li','ul']);
            console.log(target);
            //console.log(e.target);

            dragTarget = target;
            console.log(dragStart.tagName);

            if(dragStart.tagName == 'UL') {
                // source가 UL일경우, 어느 자식 노드로 이동
                console.log("서브등록");
                if(target.tagName == 'UL') {
                    console.log("ul안에 ul 이동이 되지 않습니다.");
                } else {
                    dragStart.dataset.ref = target.dataset['id'];
                    dragStart.dataset.level = target.dataset['level'] + 1; // data 속성변경
                    target.appendChild(dragStart);
                }
            } else {
                // LI 선택
                // 서로 맞교환 이동
                if(target.parentElement == dragStart.parentElement) {
                    console.log("동일 노드 맞교환");

                    targetNext = target.nextElementSibling;
                    srcNext = dragStart.nextElementSibling;
                    target.parentElement.insertBefore(dragStart, targetNext);
                    dragStart.parentElement.insertBefore(target, srcNext);

                } else {
                    console.log("LI 다른 노드 이동");
                    targetNext = target.nextElementSibling;

                    let parent = target.parentElement.parentElement;
                    console.log(parent);
                    dragStart.dataset.ref = parent.dataset['id'];

                    dragStart.dataset.level = parseInt(parent.dataset['level']) + 1; // data 속성변경
                    target.parentElement.insertBefore(dragStart, targetNext);
                }
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


        function ajaxMenuDropSync()
        {
            // 변경된 노드를 다시 확인
            let node = jinyTree.querySelectorAll('.jiny.tree > ul > li.drag-node');
            let ipos=0;
            let aaa=[];
            function __treepos(node) {
                let pos = [];
                node.forEach(el => {
                    //console.log(el);
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

                    if(sub = el.querySelectorAll('[data-ref="'+id+'"]')) {
                        pos[id].sub = __treepos(sub);
                    }

                    /*
                    if(sub = el.querySelectorAll('[data-ref="'+id+'"]')) {
                        let subtree = __treepos(sub);
                        pos[id] = {
                            'id':id,
                            'level':el.dataset['level'],
                            'ref':el.dataset['ref'],
                            'pos':el.dataset['pos'],
                            'ipos':ipos,
                            //'sub':__treepos(sub)
                            'sub':subtree
                        };
                    } else {
                        pos[id] = {
                            'id':id,
                            'level':el.dataset['level'],
                            'ref':el.dataset['ref'],
                            'pos':el.dataset['pos'],
                            'ipos':ipos,
                        };
                    }
                    */
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

            /*
            data.append('tabid', tabid)

            let tabs = document.querySelectorAll('.dragForms');
            for(let i=0; i < tabs.length; i++) {
                //data.append("pos[" + tabs[i].dataset['index'] + "]", i+1);
                data.append("pos[" + tabs[i].dataset['index'] + "]", tabs[i].dataset['tabIndex']);
            }
            */

            xhr.onload = function() {
                var data = JSON.parse(this.responseText);
                console.log(data);
            }

            xhr.send(data);


        }


         /*


        leafs.forEach(el => {



            el.addEventListener('drop', (e) => {
                e.preventDefault();
                console.log("drop");
                if(dragTarget == null) {
                    let target = findTagsParent(e.target, ['li','ul']);
                    console.log(target);
                    dragTarget = target;
                    console.log(dragStart.tagName);



                    ajaxSyncMenuSort();


                } //
            });

            el.addEventListener('dragend', (e) => {

                console.log("dragend");
                let target = findTagsParent(e.target, 'li');

                if(dragStart && dragStart.classList.contains('dragging')) {
                    dragStart.classList.remove('dragging');
                }


                dragStart = null;
                dragTarget = null;
            });

        });


        function ajaxSyncMenuSort() {
            let node = document.querySelectorAll('.drag-node');
            let posid=[];
            node.forEach(el=>{
                if (id = el.dataset['id']) {
                    posid[id] = {
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos']
                    };
                    //console.log(posid[id]);
                }
            });
            console.log("pos");
            console.log(posid);
        }



         */






    </script>


</div>
