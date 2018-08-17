Simply yii2 example
Uses 2 databases (localhost/root): nemo and cbt
Used Redis for query cache.

Easiest way to run it - using Docker. Just download docker-compose.yml flie,
and run "docker-compose up" from its location.

Docker contains 3 images, stored in my repository:
stnik/yii2-image - Apache, PHP with full yii2 sources
stnik/yii2-db1-image - MySQL server with cbt database
stnik/yii2-db1-image - MySQL server with nemo_guide_etalon database.
Full environment contains containers with these 3 images and Redis server container.

-----------------------------

Небольшое yii2 приложение, использующее одновременно 2 базы данных,
а так же redis server в качестве быстрого кеша.
Реализация ТЗ из документа ТЗ_updated_2017.docx

Для запуска приложения достаточно скачать файл download docker-compose.yml
и выполнить из каталога, где он сохранён, команду "docker-compose up"