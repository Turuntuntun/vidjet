<?php
/**
 * Created by PhpStorm.
 * User: uburov
 * Date: 04.03.2019
 * Time: 17:55
 */
ini_set('display_errors','On');
error_reporting('E_ALL');
include 'layout.php';//Подключение макета формы.
auth(); //Вызов авторизации.
//echo $a;

if(isset($_POST['submit'])){
    $count=$_POST['contact'];
    while($count){
        if($count/200>1){
            $limit=200;
        }else $limit = $count;
        for($i=0;$i<$limit;$i++){
            $contacs[] = array('name' => create_essence());
            $leads[] = array('name' => create_essence());
            $companies[] = array('name' => create_essence());
            $customers[] = array('name' => create_essence());
        }
        add($contacs, 'contacts');
        var_dump($contacs);
        $contacs = [];
        $count -= $limit;

    }

}
function add($value,$type){
    $data = array (
        'add' =>
            $value
    );
    $link = "https://uburov.amocrm.ru/api/v2/".$type;

    $headers[] = "Accept: application/json";

    //Curl options
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-
undefined/2.0");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
    $out = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($out,TRUE);
    $arr=$result['_embedded']['items'];
    foreach ($arr as $value){
        $res[] = $value['id'];
    }
}
function auth(){//Авторизация.
    $user=array(
        'USER_LOGIN'=>'uburov@team.amocrm.com', #Ваш логин (электронная почта)
        'USER_HASH'=>'1fc21263abc62f29dee2717ffb26defb02186239' #Хэш для доступа к API (смотрите в профиле пользователя)
    );
    $subdomain='uburov'; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
    /* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Вы также
    можете
    использовать и кроссплатформенную программу cURL, если вы не программируете на PHP. */
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    curl_close($curl); #Завершаем сеанс cURL
    /* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code=(int)$code;
    $errors=array(
        301=>'Moved permanently',
        400=>'Bad request',
        401=>'Unauthorized',
        403=>'Forbidden',
        404=>'Not found',
        500=>'Internal server error',
        502=>'Bad gateway',
        503=>'Service unavailable'
    );
    try
    {
        #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
        if($code!=200 && $code!=204)
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
    }
    catch(Exception $E)
    {
        die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
    }

}
function create_essence(){
    $length = rand(3,12);
    $chars = 'abcdefghiknrstyzABCDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}