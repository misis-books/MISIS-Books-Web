<div class="content-search__result-item" onclick="Page.materialClick.click(this); Page.materialClick.stopEvent(this); cancelBubbling(event)">
    <div target="_blank" class="content-search__result-item__inner">
        <div class="search-result__category-layer">
            <div class="search-result__category category-{{ category_id }}">
                <div class="search-result__circle" onclick="Page.selectCategory(this, {{ category_id }}); cancelBubbling(event);"></div>
                <div class="search-result__category-name">{{ category_name }}</div>
            </div>
            <div class="search-result__fave-add-button__layer fave-button-left">
                <div class="search-result__fave-add-button {{ fave_added }} fave-{{ edition_id }}" onclick="Page.toggleFave(this, {{ edition_id }}); return cancelEvent(event);" style="display: inline-block;"></div>
            </div>
        </div>
        <div class="search-result__edition-layer">
            <div class="search-result__header">
                <div class="search-result__title">{{ name }}</div>
            </div>
            <div class="search-result__content-layer">
                <div class="search-result__authors">
                    {{ authors }}
                </div>
                <div class="search-result__content">
                    <div class="search-result__image-layer">
                        <img class="search-result__image" src="{{ photo_small }}">
                    </div>
                    <a href="{{ href }}" target="_blank" class="button-block button-download">Загрузить</a>
                </div>
            </div>
        </div>
        <div class="search-result__fave-add-button__layer fave-button-right">
            <div class="search-result__fave-add-button {{ fave_added }} fave-{{ edition_id }}" onclick="Page.toggleFave(this, {{ edition_id }}); return cancelEvent(event);"></div>
        </div>
    </div>
</div>