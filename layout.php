<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css?v=2" >
    <title>Добавление контактов</title>
</head>
<body>
<div>
    <form action = '' method='POST'>
        <p>Добавление сущностей.</p>
        <input type='text' placeholder="Введите количество" name='contact'>
        <input type="submit" value="Добавить" name = 'submit_cont'>
    </form>
    <form action = '' method='POST'>
        <p>Добавление поля типа text.</p>
        <input type="text" placeholder="Введите текст" name = 'text'>
        <input type="text" placeholder="Введите id сущности" name = 'id_essence'>
        <input type="text" placeholder="Введите id элемента сущности" name = 'id_elem'>
        <input type="submit" value="Добавить" name = 'submit_text'>
    </form>
    <form action = '' method='POST'>
        <p>Добавление примечания.</p>
        <input type="text" placeholder="Введите примечание" name ='note'>
              <input type="text" placeholder="Введите тип примечания" name ='type_note'>
              <input type="text" placeholder="Введите id сущности куда добавить примечание" name ='id_note_essence'>
              <input type="text" placeholder="Введите элемент сущности" name ='type_note_eccence'>
              <input type="submit" value="Добавить" name = 'submit_note'>
          </form>
    </div>
</body>
</html>