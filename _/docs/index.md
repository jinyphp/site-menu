# Menu

객체 생성

```php
$Menu = \jiny\menu();
```

메뉴 데이터 읽기

```php
$m = $Menu->json("../data/menu/nav.json")->get();
```

데이터를 ul 테그로 출력하기

```php
echo $Menu->html()->ul($m);
```

json 메뉴 작성하기

```json
{
    "members":{
        "enable":true,
        "title":"회원관리",
        "href":"/admin/members",
        "controller":"",
        
        "permit":{
            "enable":true,
            "title":"권환관리",
            "href":"/admin/members/list",
            "controller":"",

            "black":{
                "enable":true,
                "title":"블랙목록",
                "href":"backip",
                "controller":""
            },
            "black2":{
                "enable":true,
                "title":"블랙목록2",
                "href":"backip",
                "controller":""
            }
        },
        "auth":{
            "enable":true,
            "title":"인증목록",
            "href":"/poem",
            "controller":""
        }
    },
    "poem":{
        "enable":true,
        "title":"시목록",
        "href":"/poem",
        "controller":""
    },
    "login":{
        "enable":true,
        "title":"로그인",
        "href":"/login",
        "controller":""
    }
}
```