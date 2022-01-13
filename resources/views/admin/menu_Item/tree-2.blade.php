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

    {{-- tree drag move --}}
    <script>
        function findTagParent(el, tag) {
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
        //console.log(leafs);
        //keypos


        let dragStart = null;
        let dragTarget = null;
        leafs.forEach(el => {

            el.addEventListener('dragstart', (e) => {
                //e.preventDefault();
                console.log("ragstart");
                // 버블링으로 한개의 이벤트만 선택
                if(dragStart == null) {
                    let target = findTagParent(e.target, ['li','ul']);
                    //let target = findClassParent(e.target, 'drag-node');
                    dragStart = target;
                    target.classList.add('dragging');
                    console.log(dragStart);
                }

                //console.log(e);
            });

            el.addEventListener('dragenter', () => {
                console.log("dragenter");
            });

            el.addEventListener('dragover', e => {
                e.preventDefault();

            })

            el.addEventListener('dragleave', () => {
                console.log("dragleave");
            });

            el.addEventListener('drop', (e) => {
                e.preventDefault();
                console.log("drop");
                if(dragTarget == null) {
                    let target = findTagParent(e.target, ['li','ul']);
                    console.log(target);
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

                    ajaxSyncMenuSort();


                } //
            });

            el.addEventListener('dragend', (e) => {

                console.log("dragend");
                let target = findTagParent(e.target, 'li');

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


        let node = document.querySelectorAll('.jiny.tree > ul > li.drag-node');
        let ipos=0;
        let aaa = [];
        function treepos(node) {
            let pos = [];
            node.forEach(el=>{
                id = el.dataset['id'];
                ipos++;
                if(sub = el.querySelectorAll('[data-ref="'+id+'"]')) {
                    aaa.push({
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'ipos':ipos,
                        'sub':treepos(sub)
                    });
                    pos[id] = {
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'ipos':ipos,
                        'sub':treepos(sub)
                    };

                    //console.log("sub");
                    //console.log(sub);
                } else {
                    aaa.push({
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'ipos':ipos
                    });
                    pos[id] = {
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'ipos':ipos,
                    };
                }

            });
            return pos;
        }

        console.log( treepos(node) );
        console.log( aaa );



        /*
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
        */


    </script>


    @push("scripts")
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    @endpush
</div>
