<?php
/**
 * Created by PhpStorm.
 * User: uburov
 * Date: 04.03.2019
 * Time: 17:55
 */
ini_set('max_execution_time', 2000);
ini_set('display_errors','On');
error_reporting('E_ALL');
//Подключение макета формы.
include 'layout.php';
//Вызов авторизации.
auth();
//Обработка формы получения сущностей
if(isset($_POST['submit_cont'])){
    $id_mult=add_custom_field('5','1',[0=>'1',1=>'2',2=>'3',3=>'4',4=>'5',5=>'6',6=>'7',7=>'8',8 =>'9',9 => '10']);
    $count=$_POST['contact'];
    //Разделеение по пакетам.
    while($count){
        if($count/500>1){
            $limit=500;
        }else $limit = $count;
        for($i=0;$i<$limit;$i++){
            //Заполнение сущностей случайными значениями.
            $contacs[] = array('name' => create_essence());
            $leads[] = array('name' => create_essence());
            $companies[] = array('name' => create_essence());
            $customers[] = array('name' => create_essence());
            //Получение массива случайно выбранных значений мультисписка.
            $random_arr[] = random_mult();
        }
        //Получение массивов id
        $id_contacs = add($contacs, 'contacts');
        $id_leads = add($leads, 'leads');
        $id_companies = add($companies, 'companies');
        $id_customers = add($customers, 'customers');
        $id_multiselect = list_multyselect_id($id_mult);
        //Заполнение мультиселекта
        select_multy('5',$id_contacs,$id_multiselect,$id_mult,$random_arr);

        //Вызов функции связи.
        connection_essence($id_contacs,$id_companies,'company_id','contacts');
        connection_essence($id_contacs,$id_leads,'leads_id','contacts');
        connection_essence($id_contacs,$id_customers,'customers_id','contacts');
        connection_essence($id_companies,$id_leads,'leads_id','companies');
        connection_essence($id_companies,$id_customers,'customers_id','companies');
        //Обнуление значений пакетов
        $random_arr=[];
        $contacs=[];
        $leads=[];
        $companies=[];
        $customers=[];
        $count-=$limit;
    }
}
//Обработка формы для изменения полей текст
if(isset($_POST['text']) and $_POST['id_essence'] and $_POST['id_elem']){
    $text = $_POST['text'];
    $id_elem = $_POST['id_elem'];
    $id_essence = $_POST['id_essence'];
    $id_text = add_custom_field('1',$id_essence, $text);
    var_dump($id_text);
    add_text($id_essence,$id_text,$id_elem,$text);
}
function add_text($id_essence,$id_text,$id_elem,$text){
    $data = array (
        'update' =>
            array (
                0 =>
                    array (
                        'id' => $id_elem,
                        'updated_at' => '1552035720',
                        'custom_fields' =>
                            array (
                                0 =>
                                    array (
                                        'id' => $id_text,
                                        'values' =>
                                            array (
                                                0 =>
                                                    array (
                                                        'value' => $text,
                                                    ),
                                            ),
                                    ),
                            ),
                    ),
            ),
    );
    $arr_eccence = ['1'=>'contacts','2'=>'leads','3'=>'companies','12'=>'customers'];
    $link = "https://uburov.amocrm.ru/api/v2/".$arr_eccence[$id_essence];

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
}
function add_note(){

}
//Проверка дублирования поля
function check_mult($type,$id_type){
    $link = 'https://uburov.amocrm.ru/api/v2/account?with=custom_fields';

    $headers[] = "Accept: application/json";

    //Curl options
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-
undefined/2.0");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
    $out = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($out,TRUE);
    $essence = ['1'=>'contacts','2'=>'leads','3'=>'companies','12'=>'customers'];
    $custom_fields = $result['_embedded']['custom_fields'][$essence[$id_type]];
    foreach ($custom_fields as $value){

        echo '<br>';
        if($value['field_type'] == $type){
            return $value['id'];
        }
    }
    return null;
}
///Заполнение поля
function select_multy($type_field,$id_contacs,$id_multiselect,$id_mult,$random_arr){
        $arr = [];
        $res = [];
        foreach ($id_multiselect as $key => $value2){
            $arr[] = $key;
        }
        foreach ($random_arr as $key=> $value){
            foreach ($value as $key2 => $value2){
                $res[$key][] = $arr[$value2];
            }
            // var_dump($res[$key]);
            //echo '<br>';
        }
        foreach ($id_contacs as $key => $value) {
            $val[$key] = array(
                'id' => $id_contacs[$key],
                'updated_at' => time(),
                'custom_fields' =>
                    array(
                        0 => array(
                            'id' => $id_mult,
                            'values' => $res[$key],
                        ),
                    ),
            );
            $result[] = $val[$key];
        }


    $data = array (
        'update' =>
            $result,
    );

    $link = "https://uburov.amocrm.ru/api/v2/contacts";

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
}
//Добавление элемента поля
function add_custom_field($type,$id_element_type,$value){
    if($type=='5'){
        $fueld_enums = array (
            'name' => 'Мультисписок',
            'type' => $type,
            'element_type' => $id_element_type,
            'origin' => '32',
            'enums' => $value,
        );
    }else if($type=='1'){
        $fueld_enums = array(
            'name' => "Тестовое поле",
            'type' => $type,
            'element_type' => $id_element_type,
            'origin' => '32',
        );
    }
    $data = array (
        'add' =>
            array (
                $fueld_enums,
            ),
    );
    //Проверка дублирования элемента
    $fl = check_mult($type,$id_element_type);

    if(!$fl){
        $link = "https://uburov.amocrm.ru/api/v2/fields";
        $headers[] = "Accept: application/json";
        $result = curl($data,$headers,$link);
        return $result['_embedded']['items'][0]['id'];
    }else return $fl;
}
//Случаынй выбор полей мультиселекта
function random_mult(){
    $count = rand(1,10);
    for($i=0;$i<=$count;$i++){
        $a = rand(0,9);
        $arr[] = $a;
    }
    return $arr;
}
//Связи сущностей
function connection_essence($essence1_id, $essence2_id, $type1, $type2){
    $newarr = [];
    foreach ($essence1_id as $key => $value){
        $newarr[] = array(
                        'id'=>$value,
                        'updated_at' => time(),
                         $type1 => $essence2_id[$key]
                    );
    }
    $data = array (
        'update' =>
            $newarr
    );
    $link = "https://uburov.amocrm.ru/api/v2/".$type2;

    $headers[] = "Accept: application/json";

    //Curl options
    $result = curl($data,$headers,$link);

}
//Добавление сущности
function add($value,$type){
    $data = array (
        'add' =>
            $value
    );
    $link = "https://uburov.amocrm.ru/api/v2/".$type;

    $headers[] = "Accept: application/json";

    //Curl options
    $result =  curl($data,$headers,$link);
    $arr=$result['_embedded']['items'];
   // $res = [];
    foreach ($arr as $value){
        if($value['id']) {
            $res[] = $value['id'];
        }
    }
    return $res;
}
//Авторизация.
function auth(){
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
//Получение id мультиселектов
function list_multyselect_id($id){
    $link = 'https://uburov.amocrm.ru/api/v2/account?with=custom_fields';

    $headers[] = "Accept: application/json";

    //Curl options
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-
undefined/2.0");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/cookie.txt");
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/cookie.txt");
    $out = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($out,TRUE);
    return $result['_embedded']['custom_fields']['contacts'][$id]['enums'];

}
//Случайное название сущностей
function create_essence(){
    $length = rand(1,25);
    $chars = 'abcdefghiknrstyzABCDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}
//Функция вызова
function curl($data,$headers,$link){
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
    return json_decode($out,TRUE);
}