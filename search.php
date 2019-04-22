<?php

include 'include/simple_html_dom.php';

// 학교정보 API, 이름, 소속, 구분, 주소, 코드 가져오기
function getSchool($search)
{
    Header('Content-Type: application/json');

    // 인코딩
    $search = iconv("UTF-8", "EUC-KR", $search);

    // url 설정
    $url = "https://www.schoolinfo.go.kr/ei/ss/Pneiss_f01_l0.do?SEARCH_SCHUL_NM=$search";

    // json 설정
    $json = array('status' => '200');

    // 학교 목록 초기화
    $schools = array();

    // 순서대로 학교목록 20개를 불러온다. (불러오는 사이트에서 그렇게 설정되어있음)
    foreach (file_get_html($url)->find('.SchoolList') as $e) {
        // 주소에서 불필요한 element 제거
        $e->find('span.InfoTitle', 1)->outertext = '';

        $officeKR = iconv("EUC-KR", "UTF-8", $e->find('span.mapD_Area', 0)->innertext);
        $officeArr = array(
            '강원' => 'kwe',
            '경기' => 'goe',
            '경남' => 'gne',
            '경북' => 'kbe',
            '광주' => 'gen',
            '대구' => 'dge',
            '대전' => 'dje',
            '부산' => 'pen',
            '서울' => 'sen',
            '세종' => 'sje',
            '울산' => 'use',
            '인천' => 'ice',
            '전남' => 'jne',
            '전북' => 'jbe',
            '제주' => 'jje',
            '충남' => 'cne',
            '충북' => 'cbe'
        );

        $office = $officeArr[$officeKR] . '.go.kr';

        // level 즉 학교의 구분을 의미한다. (euc-kr 포맷을 utf-8 포맷으로 변경한다.)
        $level = iconv("EUC-KR", "UTF-8", $e->find('span.mapD_Class', 0)->innertext);


        // 특수학교처럼 별도의 학교들은 그냥 level을 4로 통일하였다. (문제없음)
        if ($level === '초') {
            $level = 2;
        } else if ($level === '중') {
            $level = 3;
        } else {
            $level = 4;
        }

        // 주소
        $address = iconv("EUC-KR", "UTF-8", $e->find('li', 1)->innertext);

        // 이름과 코드를 가지고 있다.
        $obj = $e->find('h1.School_Name a', 0);

        // 이름
        $name = iconv("EUC-KR", "UTF-8", $obj->innertext);

        // 코드
        preg_match("/\'.*\'/iU", $obj->onclick, $match);
        $code = str_replace("'", "", $match[0]);

        $school = array('name' => $name, 'code' => $code, 'office' => $office, 'level' => $level, 'address' => $address);
        array_push($schools, $school);
    }

    $json['schools'] = $schools;

    return json_encode($json, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 사용
// echo getSchool('소프트');