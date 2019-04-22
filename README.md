# school-search-api - 전국 학교 API

### API 설명
```
이 API는 특정 검색어를 입력하면 검색어와 관련된 학교의 정보를 가져오는 API입니다.
```

### 버전 관리 (현재버전: V1.01)
V1.00
 - 전국의 모든 학교를 검색기능 구현
V1.01
 - simple_html_dom 1.8.1 업데이트

### GET
```
http://jrady721.cafe24.com/api/school/검색어 (GET)
```

> **검색어 (search)**
```
형식: 문자열
EX: 소프트
```

> **예시 (example)**  

http://jrady721.cafe24.com/api/school/소프트

> Result:
```json
{
    "status": "200",
    "schools": [
        {
            "name": "대구소프트웨어고등학교",
            "code": "D100000282",
            "office": "dge.go.kr",
            "level": 4,
            "address": "대구광역시 달성군 구지면 창리로11길 93"
        },
        {
            "name": "광주소프트웨어마이스터고등학교",
            "code": "F100000120",
            "office": "gen.go.kr",
            "level": 4,
            "address": "광주광역시 광산구 상무대로 312"
        },
        {
            "name": "대덕소프트웨어마이스터고등학교",
            "code": "G100000170",
            "office": "dje.go.kr",
            "level": 4,
            "address": "대전광역시 유성구 가정북로 76"
        }
    ]
}
```

### 활용

전국 급식 API: https://github.com/Jrady721/school-meal-api  
웹 사이트: http://jrady721.cafe24.com/meal    
구글(웨일) 확장프로그램: https://github.com/Jrady721/school-meal-extension
