<?php
include 'include/simple_html_dom.php';

// 학교정보 API, 이름, 소속, 구분, 주소, 코드 가져오기
function getSchoolData($search = '') {
    Header('Content-Type: application/json');

    if ($search === '') {
        return json_encode(array('status' => '400', 'message' => '검색어를 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    } else {
        // 인코딩
        $search = iconv("UTF-8", "EUC-KR", $search);
    }

    // url 설정
    $url = "https://www.schoolinfo.go.kr/ei/ss/Pneiss_f01_l0.do?SEARCH_SCHUL_NM=$search";

    $json = array('status' => '200');

    $schools = array();

    // 20개 까지만 (school info라는 사이트가 20개씩 보여준다..)
    foreach (file_get_html($url)->find('.SchoolList') as $e) {
        // html 제거
        $e->find('span.InfoTitle', 1)->outertext = '';

        $office = iconv("EUC-KR","UTF-8", $e->find('span.mapD_Area', 0)->innertext);

        // 교육청별로 url 주소를 다리한다.
        if($office == "서울") {
            $office = "sen.go.kr";
        } else if ($office == "경기") {
            $office = "goe.go.kr";
        } else if ($office == "강원") {
            $office = "kwe.go.kr";
        } else if ($office == "전남") {
            $office = "jne.go.kr";
        } else if ($office == "전북") {
            $office = "jbe.go.kr";
        } else if ($office == "경남") {
            $office = "gne.go.kr";
        } else if ($office == "경북") {
            $office = "kbe.go.kr";
        } else if ($office == "부산") {
            $office = "pen.go.kr";
        } else if ($office == "제주") {
            $office = "jje.go.kr";
        } else if ($office == "충남") {
            $office = "cne.go.kr";
        } else if ($office == "충북") {
            $office = "cbe.go.kr";
        } else if ($office == "광주") {
            $office = "gen.go.kr";
        } else if ($office == "울산") {
            $office = "use.go.kr";
        } else if ($office == "대전") {
            $office = "dje.go.kr";
        } else if ($office == "인천") {
            $office = "ice.go.kr";
        } else if ($office == "대구") {
            $office = "dge.go.kr";
        } else if ($office == "세종") {
            $office = "sje.go.kr";
        }

        // level 즉 학교의 구분을 의미한다. (euc-kr 포맷을 utf-8 포맷으로 변경한다.)
        $level = iconv("EUC-KR","UTF-8", $e->find('span.mapD_Class', 0)->innertext);

        if($level == "고") {
            $level = 4;
        } else if ($level == "중") {
            $level = 3;
        } else if ($level == "초") {
            $level = 2;
        } else if ($level == "특") { // 특수학교의 경우.. 그냥 level을 4로 맞춰었다. 정확히 테스트 해보지는 않았으나. level은 별로 상관없는 듯 하다.
            $level = 4;
        }

        $address = iconv("EUC-KR","UTF-8", $e->find('li', 1)->innertext);


        $obj = $e->find('h1.School_Name a', 0);
        $name = iconv("EUC-KR","UTF-8", $obj->innertext);
        // 코드
        preg_match("/\'.*\'/iU", $obj->onclick, $match);
        $code = str_replace("'","", $match[0]);

        $temp = array('name'=> $name, 'code' => $code, 'office' => $office, 'level' => $level, 'address' => $address);
        array_push($schools, $temp);
    }

    $json['schools'] = $schools;

    return json_encode($json, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

 echo getSchoolData('소프트');
?>