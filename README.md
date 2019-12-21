### Тестовое задание
 
1. Необходимо реализовать два класса форматтера (json, xml), 
само форматирование достаточно условно обозначить строками (см. пример вывода в конце)
1. Создать файл конфига в котором можно указать текущий форматтер
1. Написать клиентский класс `Client.php`, который может считывать конфиг и для всех продуктов выполнять нужное форматирование   
`Подсказка: необходимо использовать design pattern`
       
1. Интерфейс менять нельзя
1. Использовать `get_class_methods` и `Reflection` запрещено 
1. В `final` классы допускается добавления 1-го метода для реализации паттерна, 
при условии, что этот метод не будет вызывать `getDataFromN()` [этот шаг не обязателен]
1. `index.php` должен запускаться из консоли и давать следующий вывод:  
   * при включенном json форматтере:
    ```
    {"data": "ProductA data"}  
    {"data": "ProductB data"}  
    {"data": "ProductC data"}  
    ``` 
    * при включенном xml форматтере:
    ``` 
    <data>ProductA data</data>  
    <data>ProductB data</data>  
    <data>ProductC data</data>  
    ``` 
