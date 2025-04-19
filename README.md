# COACHTECH FLEAMARKET

## 環境構築

### Docker Build

1.　コードを clone する。

```
git clone {link url}
```

2.　 docker を起動する。

```
 docker compose up -d --build
```

※Mac の M1・M2 チップの PC の場合、no matching manifest for linux/arm64/v8 in the manifest list entries の
　メッセージが表示されビルドができないことがある。
エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に以下のような項目を追加してください。

```
platform: linux/x86_64
```

### Laravel 環境構築

1.

```
docker compose exec php bash
```

2.　 composer install を実行する。

3.　 cp .env.example .env を実行する。（実行後、exit で php コンテナを抜ける）

4.　.env に以下の環境変数に編集する。

```
DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```

5.　アプリケーションキーの作成(php コンテナ内）

```
php artisan key:generate
```

6.　マイグレーションの実行(php コンテナ内）

```
php artisan migrate
```

7.　シーディングの実行(php コンテナ内）

```
php artisan db:seed
```
※admin → メールアドレス　admin@example.com パスワード　　 admin123

一般ユーザー　 → 各ユーザーのメールアドレス　　パスワード　 password

8.　シンボリックリンクの作成

```
php artisan storage/link
```

※失敗した場合は、下記を実行する。
```
cd src/publilc

unlink storage

ln -s ../storage/app/public storage
```

【ER 図】

![Image](https://github.com/user-attachments/assets/1c9f3327-a0a7-470a-9638-b1eab43377d1)

【使用技術(実行環境)】
・php 8.0 ・laravel 8 ・MySQL 8.0

【URL 環境構築】
・開発環境：http://localhost/
・phpMyAdmin：http://localhost:8080/
