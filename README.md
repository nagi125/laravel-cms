# Laravel CMS

Next.js フロントエンドと Laravel API を分離した CMS の開発ベースです。ローカル環境は Docker Compose で起動します。

## 必要要件

- Docker Desktop（Docker Compose v2 を含む）
- GNU Make

## 初回セットアップ

バックエンドとフロントエンドのソースが配置された後、以下を実行します。

```bash
make setup
```

このコマンドはイメージをビルドし、PostgreSQL を起動してから Composer 依存を導入します。`src/backend/.env` がなければ `.env.example` から作成し、アプリケーションキー生成、全サービス起動、マイグレーションとシーディングまで実行します。既存の `.env` は上書きしません。

## 起動・停止

```bash
make up
make down
make restart
make ps
make logs
```

## テスト・lint

```bash
make test
make lint
make fmt
```

コンテナに入るには `make shell-php` または `make shell-frontend`、PostgreSQL に接続するには `make psql` を使います。

## ポート一覧

| ポート | 用途 |
| --- | --- |
| 8000 | Laravel API（Nginx） |
| 3000 | Next.js フロントエンド |
| 5432 | PostgreSQL |

すべての公開ポートはローカルホストからのみアクセスできます。

## ディレクトリ構成

```text
.
├── compose.yaml
├── docker/
│   ├── frontend/       # Next.js 開発用イメージ
│   ├── nginx/          # API 配信用 Nginx 設定
│   ├── php/            # PHP-FPM 8.5 と Composer
│   └── postgres/       # PostgreSQL 初期化 SQL
└── src/
    ├── backend/        # Laravel API
    └── frontend/       # Next.js
```
