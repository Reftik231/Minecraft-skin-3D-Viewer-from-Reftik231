# Minecraft-skin-3D-Viewer-from-Reftik231
Это удобный и простой 3Д просмоторщик скина маинкрафт для вашего сайта с лёгкой встраиваемостью через iframe и еастройки через get

Особенности:
- ✅Скин (Разрешением: 63x64)
- ✅Плащ (Разрешением: 63x32)
- ✅Слои скина
- ✅Анимация
- ✅Автопрокрутка
- ✅Прокрутка при зажатии мыши
- ✅Возможность выбрать модель(Alex/Stive)

# Картинки
![Черная тема](https://raw.githubusercontent.com/Reftik231/Minecraft-skin-3D-Viewer-from-Reftik231/refs/heads/main/images/dark.png "Черная тема")
![Белая тема](https://raw.githubusercontent.com/Reftik231/Minecraft-skin-3D-Viewer-from-Reftik231/refs/heads/main/images/light.png "Белая тема")

# Использование
Вставка через iframe:
```html
    <iframe style="border-radius: 50px" src="index.html
            ?name=Reftik231
            &skin=https://raw.githubusercontent.com/Reftik231/Minecraft-skin-3D-Viewer-from-Reftik231/refs/heads/main/images/Reftik231_skin.png
            &cloak=https://raw.githubusercontent.com/Reftik231/Minecraft-skin-3D-Viewer-from-Reftik231/refs/heads/main/images/Reftik231_cloak.png
            &autoRotate=true
            &walk=true
            &showUi=true
            &allowToggle=true
            theme=dark" width="300px" height="520px" frameborder="0"></iframe>
```
**Доступные параметры:**
* `name`: Текст над головой игрока.
* `skin`: Прямая ссылка на `.png` файл скина.
* `cloak`: Прямая ссылка на `.png` файл плаща. Если параметр не указан или пуст, плащ не появится.
* `autoRotate`: `false` — чтобы персонаж не крутился сам.
* `walk`: `false` — чтобы анимация ходьбы была выключена при загрузке.
* `showUi`: `false` — чтобы полностью убрать все кнопки и панели настроек (чистый просмотр).
* `allowToggle`: `false` — чтобы пользователь не мог скрыть панель (кнопка "крестик" исчезнет).
* `theme`: `light/dark` — чтобы пользователь не мог скрыть панель (кнопка "крестик" исчезнет).
