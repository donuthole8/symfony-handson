# 起動
$ symfony server:start                  
Docker Desktop起動

# URL
アプリ
https://localhost:8000/
https://127.0.0.1:8000/

MySQL管理
http://localhost:8080/

# Entityを更新した場合
symfony console make:migration             
symfony console doctrine:migrations:migrate
