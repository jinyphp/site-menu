# Menu by jinyPHP
JinyPHP Menu 관리자 입니다. 메뉴 관리위젯은 외부의 json 데이터를 기반으로 입력한 blade 기반으로 메뉴를 생성하는 위젯을 제공합니다.

## 설치
```
composer require jiny/menu
```

## 메뉴데이터
메뉴 데이터는 라라벨 `/resources/menus` 폴더안에 json 파일로 저장이 됩니다. json은 row 배열형태로 저장되며, 각각의 row에 저장된 ref, level, pos 를 기준으로 tree 로 변환을 할 수 있습니다.


## 메뉴얼
