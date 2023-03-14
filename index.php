<!DOCTYPE html>
<html>
<head>
    <title>organizer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
$date = date('H:m:s d m Y', time());
echo $date;
?>
<div id="spoiler">
    <h4>описание:</h4>
    <div id="desc" hidden>
        перед вами - мой небольшой органайзер. итак, к делу, что он может:
        <p>определяет месяц, количество дней, создает картинку календаря(php)</p>
        <p>выбирая день подгружается список дел, который уже был добавлен в этот день(js, ajax, php)</p>
        <p>можно добавлять дела(input), можно выполнять дела(checkbox), можно удалять дела(button) (js, ajax,php)</p>
        <p>можно редактировать уже сделанные или просто созданные дела</p>
        <p>если дело сделано - кнопки для удаления уже не будет. чтобы удалить дело - щелкните два раза и название дела напишите "delete!"</p>
    </div>
</div>
<table>
    <thead>
    <tr>
        <th>ПН</th>
        <th>ВТ</th>
        <th>СР</th>
        <th>ЧТ</th>
        <th>ПТ</th>
        <th>СБ</th>
        <th>ВСК</th>
    </tr>
    </thead>
    <tbody id="tbody">

<?php
$array_months = [
       1 => 31,
       2 => 28,
       3 => 31,
       4 => 30,
       5 => 31,
       6 => 30,
       7 => 31,
       8 => 31,
       9 => 30,
       10 => 31,
       11 => 30,
       12 => 31
               ];
$month = date("n", time());
$year = date("Y", time());
$countDays = $array_months[$month];
$firstDay = date('w', mktime(0,0,0, $month, 1, $year));
$body = '';
$flag = false;
for ($i = 0; $i < 42; $i++){

    if($i === 0) {
        $body .= '<tr>';
    } else if($i % 7 === 0){
        $body .= '<tr>';
    }

    if ($i + 1 == $firstDay) {
        $flag = true;
        $n = 1;
    }
    if ($flag) {
        $body .= "<td day='$n' month='$month' year='$year'><a href='#'>$n</a></td>";
        if ($n === $countDays) {
            break;
        }
        $n++;

    } else {
        $body .= '<td></td>';
    }

    if ($i != 0) {
        if ((($i + 1) % 7) === 0) {
            $body .= '</tr>';
        }
    }
}
echo $body;
?>
    </tbody>
</table>
<div id="list">выберите день</div>

<script>
    let spoiler = document.getElementById('spoiler');
    let desc = document.getElementById('desc');

    spoiler.addEventListener('click', function unsetHidden(){
       desc.removeAttribute('hidden');
       spoiler.removeEventListener('click', unsetHidden);

       spoiler.addEventListener('click', function setHidden(){
            desc.setAttribute('hidden', true);
            spoiler.removeEventListener('click', setHidden);
            spoiler.addEventListener('click', unsetHidden);
        });
    });


    let list = document.getElementById('list');
    let ul = document.createElement('ul');

    let tds = document.querySelectorAll('td');

    for(let td of tds){
        td.addEventListener('click', get_list_fetch);
    }

function del_fetch(){
    fetch('del_quest.php?id=' + this.getAttribute('id')).then(
        response => {
            return response.text();
        }
    ).then(
        text => {
            let deletedLi = document.querySelector('li, [id="'+ text+ '"]');
            deletedLi.setAttribute('hidden', true);
        }
    )
}

function add_fetch(){
    fetch('/update.php' + this.getAttribute('path') + '&value=' + this.value).then(
        response => {
            return response.json();
        }
    ).then(
        data => {
            let li = create_li(data.id, data.quest);
            ul.append(li);
        }
    );
    this.value = '';
}

function edit_fetch(){
    let input = document.createElement('input');
    input.value = this.innerHTML;
    input.setAttribute('id', this.id);
    this.innerHTML = '';
    this.append(input);
    this.removeEventListener('click', edit_fetch);

    input.addEventListener('blur', function (){
       fetch('/edit.php?id=' + this.getAttribute('id') + '&value=' + this.value).then(
           response => {
               return response.json();
           }
       ).then(
           data => {
                let span = document.querySelector('span[id="' + this.getAttribute('id') + '"]');
                span.addEventListener('click', edit_fetch);
                span.innerHTML = data.quest;
           }
       );

    });
}

function check(){
    fetch('/check.php?id=' + this.getAttribute('id')).then(
        response => {
            return response.text();
        }
    ).then(
        text => {
            alert('Вы выполнили одно из дел на сегодня! ОТЛИЧНО!');
        }
    );
    this.setAttribute('cheked',true);
    this.removeEventListener('click', check);
    this.addEventListener('click', function (event){
       event.preventDefault();
       return false;
    });
}

function get_list_fetch(){
    ul.innerHTML = '';
    if (this.getAttribute('day') != null) {
        path = '?day=' + this.getAttribute('day') + '&month=' + this.getAttribute('month') + '&year=' + this.getAttribute('year');
        fetch('/get_quests.php' + path).then(
            response => {
                return response.json();
            }
        ).then(
            data => {
                let input = document.createElement('input');
                input.setAttribute('path', path);
                input.addEventListener('blur', add_fetch);
                ul.append(input);

                for(let quest of data) {
                    let li = create_li(quest.id, quest.quest, quest.deleted);
                    ul.append(li);
                }
                list.innerHTML = '';
                list.append(ul);
            }
        );
    }

}

function create_li(id, value, deleted){
    let li = document.createElement('li');

    let span = document.createElement('span');
    span.setAttribute('id', id);
    span.innerHTML = value;
    span.addEventListener('click', edit_fetch);
    li.append(span);

    let checkbox = document.createElement('input');
    checkbox.setAttribute('type','checkbox');
    checkbox.setAttribute('id', id);
    if(deleted == 1) {
        checkbox.setAttribute('checked', true);
        checkbox.addEventListener('click', function (event){
            event.preventDefault();
            return false;
        });
        li.classList.add('wasDo');
    } else {
        checkbox.addEventListener('click', check);
        let del = document.createElement('button');
        del.innerHTML = 'X';
        del.setAttribute('id', id);
        del.addEventListener('click', del_fetch);
        li.append(del);
    }
    li.prepend(checkbox);
    return li;
}
</script>
</body>
</html>