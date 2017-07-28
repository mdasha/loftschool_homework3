<?php
/**
 * Created by PhpStorm.
 * User: Dasha
 * Date: 25.07.2017
 * Time: 22:28
 */
//Задание #1
function task1()
{

    $xml = simplexml_load_file('data.xml');
    $attrs = $xml->attributes();
    echo "<h2>Заказ #" . $attrs['PurchaseOrderNumber']. '</h2>';
    echo "<b>Дата заказа</b> " . $attrs['OrderDate'] . '<br><br>';
    //Выводим адрес доставки и адрес плательщика
    foreach ($xml->{'Address'} as $Address) {
        $attrs1 = $Address->attributes();
        switch ($attrs1) {
            case "Shipping":
                echo "<h3>Адрес доставки</h3>";
                break;

            case "Billing":
                echo "<h3>Плательщик</h3>";
                break;
        }
        echo '<b>Имя:</b> '. (string)$Address->{'Name'} . '<br><b>';
        echo 'Адрес:</b> ' . (string)$Address->{'Zip'} . ', '
            .(string)$Address->{'Country'}.', '.(string)$Address->{'State'}.", "
        .(string)$Address->{'City'}.", ".(string)$Address->{'Street'}."<br><br>";
    }
    echo "<h3>Заказанные товары</h3>";
    echo '<table border=\"1\"><tr>
        <td>Артикул</td>
        <td>Название продукта</td>
        <td>Количество</td>
        <td>Стоимость, $</td>
        <td>Дата доставки</td>
        <td>Комментарии к заказу</td>
        </tr>';
    foreach ($xml->{'Items'}->Item as $Item) {
        $attrs2 = $Item->attributes();
        echo '<tr><td>'. $attrs2.'</td>';
        echo '<td>'.$Item ->ProductName.'</td>';
        echo '<td>'.$Item ->Quantity.'</td>';
        echo '<td>'.$Item ->USPrice.'</td>';
        echo '<td>'.$Item->ShipDate.'</td>';
        echo '<td>'.$Item->Comment.'</td>';
    }
    echo '</table>';
    echo "<br><b>Примечания к заказу: </b> " . (string)$xml->{'DeliveryNotes'};
}

//Задание #2
function task2()
{
    // массив с названиями Частей Света
    $m = array('Европа','Азия','Африка','Америка','Австралия и Океания');

    // массив с названиями Стран
    $s = array('Россия','Украина','Беларусь','Казахстан');

    //массив с достопримечательностями Москвы
    $d = array('Кремль', 'Третьяковская галерея', 'Останкинская башня', 'Царицыно', 'Коломенское');

    //массив с достопримечательностями Киева
    $e = array('Крещатик', 'Дом с химерами', 'Киево-Печорская лавра', 'Площадь независимости',
        'Кивеский фуникулер');

    //массив с достопримечательностями Минска
    $f = array('Площадь Победы', 'Остров слез', 'Всехсвятская церковь', 'Кафедральный собор', 'Октябрьская площадь');

    //массив с достопримечательностями Астаны
    $g = array('Байтерек', 'Развлекательный центр Думан', 'Центральный концертный зал Казахстан',
        'Астана Цирк', 'Дворец Мира и Согласия');

    // массив с названиями Столиц и их достопримечательностей
    $c = array('Москва'=>$d,'Киев' =>$e,'Минск'=>$f,'Астана'=>$g);

    // создаём двумерный массив География
    $geography = array('Части Света'=>$m, 'Страны'=>$s, 'Столицы'=>$c);


    //Преобразуем массив в формат json, сохраняем в файл output.json
    $jsonString = json_encode($geography);
    file_put_contents('output.json', $jsonString);
    echo '1. Данные сохранены в файл output.json';


    echo '<br>2. Выводим исходный массив в формате json из файла output.json';
    //Открываем файл output.json и извлекаем из него данные
    $file_json  = file_get_contents('output.json');
    $jsonArray = json_decode($file_json, true);
    echo '<pre>';
    print_r($jsonArray);
    echo '</pre>';

    echo '<br>3. Решаем случайным образом, изменять ли массив и выводим измененный или исходный массив';
    //Решаем, изменять файл или нет. В случае изменений вносим их в содержимое
    $value = (bool)random_int(0, 1);
    echo '<pre>';
    if ($value) {
        $replacements = array('Части Света' => array('Азия', 'Европа', 'Африка', 'Америка', 'Австралия и Океания'),
            'Страны' => array('Казахстан', 'Украина', 'Беларусь', 'Молдова', 'Россия'),
            'Столицы' => array('Москва' => array('Третьяковская галерея', 'Парк им. Горького', 'Храм Христа Спасителя'
            , 'Царь Колокол')));
        $jsonArray_new = array_replace($jsonArray, $replacements);
        echo '<strong>Массив изменился</strong><br>';
        print_r($jsonArray_new);
    } else {
        echo '<strong>Массив остался прежним</strong><br>';
        $jsonArray_new = $jsonArray;
        print_r($jsonArray_new);
    }
    echo '</pre>';
    //Записываем измененный или исходный массив в новый файл output2.json
    $jsonString_new = json_encode($jsonArray_new);
    file_put_contents('output2.json', $jsonString_new);

    // Открываем файл output2.json и извлекаем из него данные
    $file_json_new  = file_get_contents('output2.json');
    $jsonArray_new_file = json_decode($file_json_new, true);


    //Открываем файл output.json
    $file_json_initial  = file_get_contents('output.json');
    $jsonArray_initial_file = json_decode($file_json_initial, true);


    //Сравниваем массивы, находящиеся в файлах output.json и output2.json и выводим различия


    echo '<br>4. Сравниваем массивы, находящиеся в файлах output.json и output2.json и выводим различия<br>';

    //Превращаем многомерный ассоциативный массив в одномерный числовой массив для последующего сравения:

    $AssocIntoNumber = function ($EntranceArray) {
        $i = 0;
        $initialArray = [];
        foreach ($EntranceArray as $key1 => $v1) {
            foreach ($v1 as $key2 => $v2) {
                if (gettype($v2)=='array') {
                    foreach ($v2 as $v3) {
                        $i++;
                        $initialArray[$i] = 'Элемент № '. $i . '--'. $key1 .'--'. $key2. '--' . $v3;
                    }
                } else {
                    $i++;
                    $initialArray[$i] = 'Элемент №'. $i . '--'. $key1 .'--' . $v2;
                }
            }
        }
        return $initialArray;
    };

    // Преобразуем исходный массив в одномерный
    $result1 = $AssocIntoNumber($jsonArray_initial_file);
    // Преобразуем измененный массив в одномерный
    $result2 = $AssocIntoNumber($jsonArray_new_file);

    if ($result1<>$result2) {
        echo '<br><h3>Выводим различающиеся элементы:</h3><table border=\"1\">
    <thead>
    <td><b>#</b></td>
    <td><b>Элемент в исходном массиве</b></td>
    <td><b>Элемент в измененном массиве</b></td></thead>
    ';
        $k = 1;
        for ($i =1; $i< count($result1); $i++) {
            if ($result1[$i]<>$result2[$i]) {
                echo '<tr><td>' . $k . '</td><td>';
                echo $result1[$i];
                echo '</td><td>' . $result2[$i] . '</td></tr>';
                $k++;
            }
        }
        echo '</table>';
    } else {
        echo 'Исходный и измененный массив не различаются';
    }
}

//Задание #3
function task3()
{
    //Генерируем случайное число от 50 до 100 - количество элементов массива
    $ArrayLength = random_int(50, 100);
    $RandomNumbersArray =[];
    //Создаем массив случайных чисел от 1 до 100
    for ($i = 0; $i <= $ArrayLength - 1; $i++) {
        $RandomNumbersArray[$i] = rand(1, 100);
    }
    //Выводим созданный массив на экран
    echo 'Выводим созданный программно массив на экран<pre>';
    print_r($RandomNumbersArray);
    echo '</pre>';
    // Открываем csv-файл на запись и вносим созданный массив в файл
    $fp_csv = fopen('test.csv', 'w+');
    fputcsv($fp_csv, $RandomNumbersArray);
    fclose($fp_csv);
    echo 'Файл test.csv успешно записан<br>';

    echo 'Открываем файл csv и считаем сумму четных чисел';
    $csvPath = 'test.csv';
    $csvFile = fopen($csvPath, "r");
    if ($csvFile) {
        $res = array();
        while (($csvData = fgetcsv($csvFile, 1000, ",")) !== false) {
                $res = $csvData;
        }
        $even_sum = 0;
        $res_even = array();
        foreach ($res as $even) {
            if ($even%2 ==0) {
                $even_sum += (int)$even;
                $res_even[] = $even;
            }
        }
        echo '<br><h2>Сумма четных чисел в csv-файле: '. $even_sum.'</h2>';
        echo '<h3>Выводим массив, состоящий только из четных элементов</h3>';
        echo '<pre>';
        print_r($res_even);
        echo '</pre>';
    }
}

//Задание #4
function task4()
{
    $url =
        "https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result1 = curl_exec($curl);
    curl_close($curl);
    // Переводим полученные данные в json-формат
    $result2 = json_decode($result1);
    // Выводим page_id и title из полученных данных
    echo 'page_id: ';
    print_r($result2->query->pages->{'15580374'}->pageid);
    echo '<br>title: ';
    print_r($result2->query->pages->{'15580374'}->title);
}
