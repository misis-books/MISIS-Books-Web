<div class="dev_title">
    <a name="m" class="dev_name_anchor"></a>
    materials.getCategories
    <a title="Ссылка на раздел" href="#m" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.getCategories#m', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_p">
    Возвращает актуальный список названий категорий в формате <b>key</b>, <b>value</b>.
    <br>
    key — идентификатор категории.
    <br><br>
    <div class="dev_title_sub">
        <a name="params" class="dev_name_anchor"></a>
        Параметры
        <a title="Ссылка на раздел" href="#params" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.getCategories#params', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_p">
        <div class="dev_blockquote">
            Данный метод не имеет своих параметров.
        </div>
    </div>
    <div class="dev_title_sub">
        <a name="retvals" class="dev_name_anchor"></a>
        Возвращаемые поля
        <a title="Ссылка на раздел" href="#retvals" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#retvals', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_p">
        Объект <b>response</b> содержит следующие поля:
        <br><br>
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>items_count</b> — количество актуальных категорий.</span>
                <br>
                <span class="dev_type_description">положительное число</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>key</b> — идентификатор категории.</span>
                <br>
                <span class="dev_type_description">число от <b>1</b> до <b>8</b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category_name</b> — название категории. Соответствует идентификатору <b>key</b>.</span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>color_hex</b> — соответствующий цвет категории в HEX.</span>
                <br>
                <span class="dev_type_description">hexadeсimal</span>
            </li>
        </ul>
    </div>
</div>