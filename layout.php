<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css?v=2" >
    <title>Добавление контактов</title>
</head>
<body>
    <div class="first_block">
        <form action = '' method='POST'>
            <p>
                Добавление сущностей.
            </p>
            <input type='text' placeholder="Введите количество" name='contact'>
            <input type="submit" value="Добавить" name = 'submit_cont'>
        </form>
        <form action = '' method='POST'>
            <p>
                Добавление поля типа text.
            </p>
            <input type="text" placeholder="Введите текст" name = 'text'>
            <select name ='id_essence'">
                <option>Введите сущность</option>
                <option value="1">Контакты</option>
                <option value="2">Сделки</option>
                <option value="3">Компании</option>
                <option value="12">Покупатели</option>
            </select>

            <input type="text" placeholder="Введите id элемента сущности" name = 'id_elem'>
            <input type="submit" value="Добавить" name = 'submit_text'>
        </form>
        <form action = '' method='POST'>
            <p>Добавление примечания.</p>
            <input type="text" placeholder="Введите примечание" name ='note'>
            <select name ='type_note'">
                <option>Введите тип примечания</option>
                <option value="4">Текстовое</option>
                <option value="10">Звонок</option>
            </select>
            <input type="text" placeholder="Введите id сущности" name ='type_note_eccence'>
            <select name ='id_note_essence'">
                <option>Введите сущность</option>
                <option value="1">Контакты</option>
                <option value="2">Сделки</option>
                <option value="3">Компании</option>
                <option value="12">Покупатели</option>
            </select>
            <input type="submit" value="Добавить" name = 'submit_note'>
        </form>
    </div>
    <div class = 'second_block'>
        <form action="" method="POST">
            <p>
                Добавление задачи.
            </p>
            <select name ='id_essence_task'">
                <option>Введите сущность</option>
                <option value="1">Контакты</option>
                <option value="2">Сделки</option>
                <option value="3">Компании</option>
                <option value="12">Покупатели</option>
            </select>

            <input type='text' placeholder="Введите id элемента" name="id_elem_text">
            <input type='text' placeholder="Введите текст задачи" name="text_task">
            <input type="datetime-local" name="date_tusk">
            <select name ='id_task'">
                <option>Введите ответсвенного пользователя</option>
                <option value="24971122">Админ</option>
            </select>
            <input type="submit" value="Добавить" name = 'submit_task'>
        </form>
        <form action="" method="POST">
            <p>
                Завершение задачи.
            </p>
            <input type="text" name="id" nama="end_task_id" placeholder="Введите id задачи">
            <input type="submit" value="Завершить задачу" name = 'end_task'>
        </form>
    </div>
</body>
</html>