<?php
include 'include/simple_html_dom.php';

//$search = "대구소프트웨어";
//
//if($_GET) {
//    $search = $_GET['search'];
//}
//
//$search = iconv("UTF-8", "EUC-KR", $search);
//$url = "https://www.schoolinfo.go.kr/ei/ss/Pneiss_f01_l0.do?SEARCH_SCHUL_NM=$search";
//
//// 20개 까지만 보여준다.
//foreach (file_get_html($url)->find('.SchoolList') as $e) {
//    // html 제거
//    $e->find('span.InfoTitle', 1)->outertext = '';
//    echo '지역: '.iconv("EUC-KR","UTF-8", $e->find('span.mapD_Area', 0)).'<br>';
//    echo '구분: '.iconv("EUC-KR","UTF-8", $e->find('span.mapD_Class', 0)).'<br>';
//    echo '주소: '.iconv("EUC-KR","UTF-8", $e->find('li', 1)->innertext).'<br>';
//
//
//    $obj = $e->find('h1.School_Name a', 0);
//    $schoolName = iconv("EUC-KR","UTF-8", $obj->innertext);
//    preg_match("/\'.*\'/iU", $obj->onclick, $match);
//    $match = str_replace("'","", $match[0]);
//    echo "명칭: ". $schoolName.'<br>';
//    echo "코드: ". $match.'<br><br>';
//}

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


    // 20개 까지만
    foreach (file_get_html($url)->find('.SchoolList') as $e) {
        // html 제거
        $e->find('span.InfoTitle', 1)->outertext = '';

        $office = iconv("EUC-KR","UTF-8", $e->find('span.mapD_Area', 0)->innertext);
        $level = iconv("EUC-KR","UTF-8", $e->find('span.mapD_Class', 0)->innertext);
        $address = iconv("EUC-KR","UTF-8", $e->find('li', 1)->innertext);


        $obj = $e->find('h1.School_Name a', 0);
        $name = iconv("EUC-KR","UTF-8", $obj->innertext);
        // 코드
        preg_match("/\'.*\'/iU", $obj->onclick, $match);
        $code = str_replace("'","", $match[0]);

        $temp = array('code' => $code, 'office' => $office, 'level' => $level, 'address' => $address);
        $json[$name] = $temp;
    }

//    print_r($json);
    return json_encode($json, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

echo getSchoolData('소프트');
?>