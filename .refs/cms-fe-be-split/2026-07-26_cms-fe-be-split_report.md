# CMS ベース構築（フロントエンド / バックエンド分離）総合レポート

| 項目 | 内容 |
|---|---|
| 作成日 | 2026-07-26 |
| 要件 | Laravel 単体のモノリスだった `nagi125/laravel-cms` を、`src/backend`（Laravel）と `src/frontend`（Next.js + TypeScript + Tailwind）に分離し、PostgreSQL・docker-compose・Debian trixie ベースの Nginx + PHP-FPM 8.5 で「すぐ開発に着手できる CMS のベース」を構築する |
| ブランチ | `feature/loop-develop/cms-fe-be-split`（base: `main`） |
| 周回数 | 3 周（Round 1: 新規構築 / Round 2: Medium 解消 / Round 3: 最終レビューと High 1件の修正） |
| 変更規模 | 284 files changed, 13,223 insertions(+), 20,981 deletions(-)（新規 89 / 削除 171） |

## TL;DR — 最終品質判定

| 観点 | 判定 | 根拠 |
|---|---|---|
| 要件充足 | ✅ 達成 | 受け入れ条件 14 項目すべて実測で確認（後述「要件と達成度」） |
| バックエンドテスト | ✅ グリーン | `php artisan test` → **31 passed / 192 assertions** |
| バックエンド lint | ✅ グリーン | `./vendor/bin/pint --test` → **PASS（53 files）** |
| フロントエンド lint | ✅ グリーン | `npm run lint` → **0 エラー** |
| フロントエンドビルド | ✅ 成功 | `npm run build` → 7 ルート生成 |
| ローカル環境の起動 | ✅ 成功 | `make setup` 完走 → 4 サービス起動（db healthy） |
| 実 API / 画面の疎通 | ✅ 全項目成功 | 統合検証 **24/24 pass** |
| 新規 clone からの再現性 | ✅ 確認済み | clone 後に `vendor` / `.env` を含まず、storage・bootstrap のプレースホルダ 9 個 + 1 個が追跡されている |
| 未解決の Critical/High | ✅ ゼロ | 検出した Critical/High は全 7 件すべてこのブランチ内で修正・実測確認済み |

## 技術選定（一次情報で確認・2026-07-26 時点）

| 項目 | 採用 | 確認内容 |
|---|---|---|
| ベース OS | `debian:trixie-slim` | **trixie は Debian 13 のコードネームであり Ubuntu には存在しない**。要件の「ubuntu の trixie」は Debian trixie としてユーザー確認のうえ確定 |
| PHP | **8.5.8**（php-fpm） | Debian trixie 標準リポジトリは PHP 8.4 のため **deb.sury.org** から導入。`php8.5-fpm 8.5.8-1+0~20260703.21+debian13~1` の実在を amd64 / arm64 の Packages インデックスで確認 |
| Nginx | Debian trixie 標準パッケージ | `debian:trixie-slim` + `apt-get install nginx` |
| Laravel | **13.8 系**（最新安定メジャー） | PHP 8.3–8.5 対応。テストは PHPUnit 12.5、lint は Laravel Pint 1.27（スケルトン既定） |
| 認証 | **Laravel Sanctum 4**（SPA Cookie 認証） | トークン認証ではなく Laravel 公式が SPA に推奨する方式 |
| Next.js | **16.2.12** | App Router / Turbopack / `src` ディレクトリ / import alias `@/*` |
| Tailwind CSS | **v4** | PostCSS プラグインは `@tailwindcss/postcss`、CSS は `@import "tailwindcss";`（v3 記法は不使用） |
| PostgreSQL | **`postgres:18-trixie`** | Docker Hub で実在を確認したタグ |
| Node | **`node:24-trixie-slim`** | Node 24 LTS |

**注**: `php8.5-opcache` というパッケージは sury に存在せず、OPcache は `php8.5-cli` に静的に組み込まれている（実測確認）。

## 構成

```
laravel-cms/
├── compose.yaml            db / php / nginx / frontend の4サービス
├── Makefile                setup / up / down / test / lint / fmt / shell-* / psql
├── README.md               必要要件・セットアップ・起動停止・test/lint・ポート・構成
├── docker/
│   ├── php/                debian:trixie-slim + sury php8.5-fpm + Composer
│   ├── nginx/              debian:trixie-slim + nginx（public 配信 + FastCGI）
│   ├── frontend/           node:24-trixie-slim
│   └── postgres/initdb/    テスト用 DB `cms_test` の作成
├── .github/workflows/ci.yml  backend（Pint + PHPUnit）/ frontend（lint + build）
└── src/
    ├── backend/            Laravel 13（API 専用。Vite/npm 資産は除去済み）
    └── frontend/           Next.js 16（公開2画面 + 管理5画面）
```

| ポート（すべて 127.0.0.1 限定） | 用途 |
|---|---|
| 8000 | Laravel API（Nginx → PHP-FPM） |
| 3000 | Next.js 開発サーバー |
| 5432 | PostgreSQL |

### API

| method | path | 認証 | 成功 |
|---|---|---|---|
| GET | `/api/health` | 不要 | 200 |
| GET | `/sanctum/csrf-cookie` | 不要 | 204 |
| POST | `/api/auth/login` | 不要 | 200（失敗 422・**5回/分でレート制限**） |
| POST | `/api/auth/logout` | 要 | 204 |
| GET | `/api/auth/me` | 要 | 200 |
| GET | `/api/posts` / `/api/posts/{slug}` | 不要 | 200（published のみ。draft は 404） |
| GET/POST/PUT/DELETE | `/api/admin/posts[/{id}]` | 要 | 200 / 201 / 204 |

レスポンスは Laravel 既定形式（単一は `{"data":…}`、一覧は `data` + `links` + `meta`、エラーは `{"message":…, "errors":…}`）。

## 要件と達成度

| # | 受け入れ条件 | 達成 | 根拠 |
|---|---|---|---|
| AC-1 | `src/backend` に Laravel、`src/frontend` に Next.js が存在し、ルート直下に旧 Laravel 一式が無い | ✅ | 旧アプリ 193 ファイル削除。clone 後のトップレベルは `compose.yaml docker Makefile README.md src` 等のみ |
| AC-2 | `docker compose up -d` で 4 サービスが起動（db は healthy） | ✅ | `docker compose ps` → db(healthy) / php / nginx / frontend すべて running |
| AC-3 | PHP 8.5 かつ `pdo_pgsql` が有効 | ✅ | `php -v` → PHP 8.5.8 / `php -m` に `pdo_pgsql` `Zend OPcache` `bcmath` `intl` `mbstring` `zip` |
| AC-4 | `/api/health` が 200 | ✅ | `curl` → 200 `{"status":"ok"}` |
| AC-5 | `migrate` 成功・`posts` 作成 | ✅ | `make setup` 完走。11 テーブル（`posts` `sessions` 含む） |
| AC-6 | 認証フロー | ✅ | 未認証 me→401 / login→200 / me→200 / logout→204 / 以後 me→401（実 Cookie + CSRF） |
| AC-7 | 管理 CRUD と未認証 401 | ✅ | POST→201 / GET→200 / PUT→200 / DELETE→204、未認証はすべて 401。テスト `AdminPostTest` |
| AC-8 | 公開 API が draft を返さない | ✅ | 一覧の draft 0 件 / draft slug → 404。テスト `PublicPostTest::public_index_does_not_include_drafts` 他 |
| AC-9 | `php artisan test` 全パス | ✅ | **31 passed / 192 assertions** |
| AC-10 | `pint --test` 指摘ゼロ | ✅ | PASS（53 files） |
| AC-11 | フロント lint 0・build 成功 | ✅ | lint 0 エラー / build 7 ルート |
| AC-12 | 7 画面が 200 | ✅ | `/` `/posts/{slug}` `/admin/login` `/admin/posts` `/admin/posts/new` `/admin/posts/{id}/edit` すべて 200 |
| AC-13 | Tailwind v4 方式 | ✅ | `@tailwindcss/postcss` + `@import "tailwindcss";`、v3 記法の残存なし |
| AC-14 | README の手順で再現できる | ✅ | README 記載の `make setup` を実行して AC-2〜AC-5 を再現 |

## 周回ごとの変遷

### Round 1 — 新規構築
- 旧 Laravel 10 アプリを削除（tracked 193 + `vendor` `node_modules` `bootstrap` `public` `storage` `.docker`）
- Wave 1（並列）: インフラ基盤 / フロントエンド、Wave 2: バックエンド
- **Critical/High 6 件を検出しその周で修正**:
  1. `php8.5-opcache` が sury に存在せず php イメージがビルド不能（`E: Unable to locate package`）
  2. php-fpm の pool に `pm` が無く起動不能（`the process manager is missing`）
  3. フロントの lint 9 件（`react-hooks/error-boundaries`: try/catch 内での JSX 構築）
  4. `status=published` + `published_at=null` の記事が作成でき、公開一覧・詳細から**永久に見えない**
  5. 非数値の記事 ID が **500**（TypeError）となり内部パスが露出
  6. ログイン試行に**レート制限が無い**（12 連続失敗でも 429 が出ない）

### Round 2 — Medium 解消
1. リバースプロキシ経由でページネーション URL からポート 8000 が欠落 → nginx が `X-Forwarded-Host` / `X-Forwarded-Proto` を転送し Laravel が `trustProxies` で信頼
2. SPA Cookie / CORS の自動テストが無い → Feature テスト化
3. ページ送り UI が無く 16 件目以降に到達不能 → 公開一覧（`?page=N`）と管理一覧に前へ/次へを実装
4. 公開ポートが全インターフェースに bind → **127.0.0.1 限定**に変更
5. `SANCTUM_STATEFUL_DOMAINS` と CORS・`SESSION_DOMAIN` の不整合 → `localhost:3000` に統一

### Round 3 — 最終レビュー（検証専用 + High 1 件の修正）
- **High**: `PostForm` が API の UTC 日時を `slice` してそのまま `datetime-local` に渡し、送信時もタイムゾーン無しで送っていたため、
  JST 環境で公開日時が **9 時間ずれる**（編集・保存のたびに時刻が変わる）→ UTC ⇔ ローカルの明示的な相互変換に修正
- Medium（テスト不足）2 件を追加対応: CSRF 検証の回帰テスト（トークン無し → 419 / 正しいトークン → 成功）、
  管理系 GET の未認証 401 テスト

## リーダー × codex レビュー統合

| 分類 | 件数 | 内容 |
|---|---|---|
| 両レーンで共通して検出 | 1 | ログインのレート制限なし（リーダー L1 = codex C2） |
| codex のみが検出（Critical/High） | 3 | `published_at=null` の永久非表示 / `Rule::unique()->ignore()` へのユーザー制御値（あわせて非数値 ID の 500 が判明）/ PostForm のタイムゾーンずれ |
| リーダーのみが検出（Critical/High） | 4 | php イメージのビルド失敗 / php-fpm 起動不能 / フロント lint 9 件 / CI のテスト DB 名不整合（いずれもビルド・テスト・実 API の実行で検出） |
| codex ② がリーダー指摘を訂正 | 5 | リーダーの 9 指摘のうち 3 件を「誤り」、2 件を「過剰」と判定。リーダーは該当指摘を取り下げ・降格した |
| 要件外の変更（revert 指示） | **0** | 領域外の変更・指示していないリファクタの検出なし |

### レーン別実績

| レーン | Critical/High 指摘 | 重複 | 単独発見 | 備考 |
|---|---|---|---|---|
| リーダー（レビュー） | 1 | 1 | 0 | 静的レビュー単独では 1 件 |
| リーダー（実行検証: ビルド・テスト・実 API） | 4 | 0 | 4 | **メンバーが Docker・ネットワークに到達できないため実行検証はすべてリーダーが担当** |
| codex ①（code-reviewer + security-reviewer） | 4 | 1 | 3 | 名指しspawnを `collab_tool_call` イベントで確認（Round 1: 7 件 / Round 3: 12 件） |
| codex ②（指摘検証） | 0 | — | — | 新規 High の追加なし。リーダー指摘 9 件中 5 件の結論を変更 |

**費用対効果の所見**: codex ①の独立レビューは静的解析でしか気づけない設計上の欠陥（公開条件の不整合・タイムゾーン・
公式ガイドライン違反）を単独で 3 件発見しており、費用に見合っている。codex ②は新規欠陥こそ出さないが、
リーダー指摘の 5 件を誤り・過剰と判定してノイズを除去した。一方、ビルド・テスト・実 API の**実行**でしか
検出できない欠陥が 4 件あり、これはレビューでは代替できない。

## テスト・lint サマリー

| コマンド | 結果 |
|---|---|
| `docker compose exec php php artisan test` | **31 passed / 192 assertions** |
| `docker compose exec php ./vendor/bin/pint --test` | **PASS（53 files）** |
| `npm run lint`（src/frontend） | **0 エラー** |
| `npm run build`（src/frontend） | **成功（7 ルート）** |
| `make setup` | 完走（build → db 起動 → composer install → .env 生成 → key:generate → 全起動 → migrate --seed） |
| 統合検証（実 API + 画面 24 項目） | **24/24 pass** |

Feature テストの内訳: 認証（ログイン成功 / 資格情報不一致 422 / 未認証 401 / 認証済み 200 / レート制限 429 /
CSRF トークン無し 419 / CSRF 有効時の成功 / ログアウトと 401 / CORS プリフライト許可・不許可）、
管理 CRUD（作成 201 / 更新 200 / 削除 204 / 未認証 401（作成・更新・削除・一覧・詳細）/ バリデーション 422 /
slug 重複 422 / 非数値 ID 404 / 存在しない ID 404 / ステータス絞り込み / 即時公開 / 予約公開 / draft）、
公開 API（health / draft 非表示 / 公開詳細 200 / draft slug 404 / `per_page` 既定値 / `meta.path`）。

## 残課題・フォローアップ（別 PR 候補）

| # | 内容 | 理由 |
|---|---|---|
| 1 | **ロール / 所有者ベースの認可** — 現状は認証済みユーザーなら誰でも全記事を CRUD できる | 権限設計は本要件のスコープ外 |
| 2 | **本番用 Dockerfile とデプロイパイプライン** — マルチステージ・opcache 最適化・非 root 実行。旧 `ecs.yml` は削除済み | 本 PR はローカル開発環境が対象 |
| 3 | **プロキシ信頼範囲の限定** — 現状 `trustProxies(at: '*')`。本番では実際のプロキシ IP に絞る | ローカル構成では php への入口が nginx のみ |
| 4 | **カテゴリ / タグ・メディア（画像アップロード）機能** | 機能スコープ外 |
| 5 | **フロントの E2E / ユニットテスト（Playwright / Vitest）** | テスト基盤の整備は別 PR |
| 6 | **スモークテストスクリプトのリポジトリ同梱** — 本ループで使用した実 API 検証スクリプトはセッション作業領域にのみ存在する | 本 PR のスコープ外 |
| 7 | 不正な `status` クエリを 422 で弾く（現状は「絞り込みなし」として扱う） | Low |
| 8 | `src/backend/.gitignore` に残る Vite 資産向けエントリの整理 | Low |
| 9 | 認証まわりの拡充（パスワードリセット・メール認証） | スコープ外 |
| 10 | シードの管理者資格情報を固定値から変更可能にする | ローカル開発専用として意図的に固定。README に記載 |

## 変更サマリー

```
284 files changed, 13223 insertions(+), 20981 deletions(-)
新規追加 89 ファイル（src/backend 56 / src/frontend 23 / docker 7 / compose.yaml / Makefile / .github）
削除 171 ファイル（旧 Laravel 10 モノリス一式）
```

コミットは周ごとの checkpoint を残している（`round 1` / `round 2` / `round 3`）。

## 参考文献

- PHP: リリース一覧 https://www.php.net/releases/ / サポート方針 https://www.php.net/supported-versions.php
- Laravel 13 リリースノート https://laravel.com/docs/13.x/releases
- Laravel Sanctum（SPA 認証） https://laravel.com/docs/13.x/sanctum
- Laravel Pint https://laravel.com/docs/13.x/pint
- Laravel バリデーション（`Rule::unique()->ignore()` の注意） https://laravel.com/docs/13.x/validation
- Next.js インストール https://nextjs.org/docs/app/getting-started/installation / `create-next-app` https://nextjs.org/docs/app/api-reference/cli/create-next-app
- Tailwind CSS v4 の Next.js 導入 https://tailwindcss.com/docs/installation/framework-guides/nextjs
- PostgreSQL 18 リリース https://www.postgresql.org/about/news/postgresql-18-released-3142/
- Debian 13 (trixie) リリース https://www.debian.org/News/2025/20250809
- deb.sury.org PHP リポジトリ https://packages.sury.org/php/README.txt
- Debian trixie の nginx パッケージ https://packages.debian.org/trixie/amd64/nginx
- Node.js リリース一覧 https://nodejs.org/en/about/previous-releases
