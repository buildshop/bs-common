<?php
$docs = array(
    array(
        'name' => 'name',
        'type' => 'string',
        'required' => 'Да',
        'desc' => 'Название товара'
    ),
    array(
        'name' => 'type',
        'type' => 'string',
        'required' => 'Да',
        'desc' => 'Тип товара'
    ),
    array(
        'name' => 'price',
        'type' => 'float',
        'required' => 'Да',
        'desc' => 'Цена'
    ),
    array(
        'name' => 'images',
        'type' => 'array',
        'required' => '',
        'desc' => '
Изображение товара<br/>
<br>
<ul>
    <li><code>deleteOld</code> - поумолчание равен "false" (не обазятельный)<br>Удаляет текущие изображение если таковы имеются.</li>
</ul>

<br>
Пример:<br>
<pre class="code">
&lt;images deleteOld="true"&gt;
    &lt;image&gt;filename.jpg&lt;/image&gt;
    ...и т.д...
&lt;/images&gt;
</pre>
'
    ),
    array(
        'name' => 'attributes',
        'type' => 'array',
        'required' => '',
        'desc' => '
Характеристики товара<br/>
<br>
<ul>
    <li><code>name</code> - обазятельный<br>Удаляет текущие изображение если таковы имеются.</li>
</ul>

<br>
Пример:<br>
<pre class="code">
&lt;attributes&gt;
    &lt;attribute name="attrName"&gt;filename.jpg&lt;/attribute&gt;
    ...и т.д...
&lt;/attributes&gt;
</pre>
'
    ),
);
?>


<table class="table table-striped">
    <tr>
        <th>Параметр</th>
        <th>Обязательный</th>
        <th>Тип</th>
        <th>Описание</th>
    </tr>
    <?php foreach ($docs as $row) { ?>
        <tr>
            <td><b><?= $row['name'] ?></b></td>
            <td><?= $row['required'] ?></td>
            <td><?= $row['type'] ?></td>
            <td><?= $row['desc'] ?></td>
        </tr>
    <?php } ?>
</table>