<div class="dev_title">
    <a name="m" class="dev_name_anchor"></a>
    materials.getPopular
    <a title="Ссылка на раздел" href="#m" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.getPopular#m', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_p">
    Возвращает список популярных материалов. (Выборка с сортировкой по убыванию количества скачиваний)
    <br><br>
    <div class="dev_title_sub">
        <a name="params" class="dev_name_anchor"></a>
        Параметры
        <a title="Ссылка на раздел" href="#params" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.getPopular#params', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_p">
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>count</b> — количество возвращаемых материалов.</span>
                <br>
                <span class="dev_type_description">положительное число, по умолчанию <b>10</b>, максимальное значение <b>200</b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category</b> — идентификатор категории. Актуальный список можно получить <a class="dev_link" href="/dev/materials.getCategories">здесь</a>.</span>
                <br>
                <span class="dev_type_description">число от <b>1</b> до <b>8</b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>offset</b> — смещение относительно первого материала для выборки определенного подмножества.</span>
                <br>
                <span class="dev_type_description">неотрицательное число, по умолчанию <b>0</b></span>
            </li>
        </ul>
    </div>
</div>