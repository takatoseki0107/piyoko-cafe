# ぴよこcafe - WordPressオリジナルテーマ

> 山の古民家を改装した、たまご料理のカフェ（架空）
> HTMLコーディングからWordPressテーマ化までを一貫して制作したポートフォリオです。

---

## コンセプト

架空のカフェサイトを題材に、**静的HTMLの制作からWordPressテーマ化までの一連の流れ**を実装しました。

デザインは既存テンプレートを使わず、色・余白・タイポグラフィのルールを先に決めてからコーディングしています。実装後の運用（クライアントが自分でコンテンツを更新すること）を前提に、管理画面から編集できる範囲を広く取りました。

---

## デザインルール

| 項目 | 内容 |
|---|---|
| カラー | `#F2B705`（たまご） / `#8A6240`（木） / `#FFFDF7`（背景） / `#3E2C1C`（文字） / `#7A6A5C`（グレー） |
| 余白 | 8 / 16 / 32 / 64 / 120px の5段階のみ |
| フォント | 見出し: Zen Maru Gothic ／ 本文: Noto Sans JP |
| レイアウト | 最大幅 1120px ／ 角丸 16px |
| 命名規則 | BEM（`.block__element--modifier`） |

余白を5段階に固定し、色をCSS変数で管理することで、デザインの一貫性をコードレベルで担保しています。この値はそのまま `theme.json` のデザイントークンとしても定義しました。

---

## 技術構成

| レイヤー | 技術 |
|---|---|
| マークアップ | HTML5 / CSS3（BEM設計・CSS変数） |
| スクリプト | Vanilla JavaScript |
| CMS | WordPress 6.x（クラシックテーマ + theme.json） |
| 開発環境 | Docker Compose（WordPress + MySQL 8.0） |
| バージョン管理 | Git / GitHub（ブランチ運用 + PR） |

---

## サイト構成

| ページ | テンプレート | 内容 |
|---|---|---|
| トップ | `front-page.php` | ヒーロー / コンセプト / おすすめメニュー3件 / お知らせ3件 / アクセス |
| メニュー一覧 | `archive-menu.php` | カスタム投稿をカテゴリー別に表示 |
| メニュー詳細 | `single-menu.php` | 商品画像・価格・説明 |
| お店について | `page.php` | 固定ページ |
| お知らせ一覧 | `home.php` | 投稿一覧・ページ送り |
| お知らせ詳細 | `single.php` | 記事本文・前後の記事へのリンク |
| アクセス | `page.php` | 固定ページ |

---

## 実装内容

### 1. HTMLファイルからのテーマ化

静的HTMLで全ページを制作したあと、共通パーツを分割してWordPressテーマに移行しました。

- `header.php` / `footer.php` に共通パーツを切り出し、`get_header()` / `get_footer()` で呼び出し
- テンプレート階層に沿ってファイルを配置（`front-page.php` / `page.php` / `single.php` / `archive-*.php`）
- CSS・JavaScript は `wp_enqueue_style()` / `wp_enqueue_script()` で登録
- `filemtime()` をバージョン番号に渡し、更新時のブラウザキャッシュを回避
- 画像・アセットのパスは `get_theme_file_uri()` で解決（ドメイン変更に影響されない）
- 出力値は `esc_url()` / `esc_html()` / `esc_attr()` でエスケープ

### 2. 子テーマ

親テーマ `cafe-theme` を継承する子テーマ `cafe-theme-sakura` を作成しました。春季限定の配色に切り替えます。

- `style.css` のヘッダーコメントに `Template: cafe-theme` を指定
- **テンプレートファイルは1つも複製せず**、CSS変数の再定義のみで全ページの配色を変更
- `get_stylesheet_uri()` で子テーマのCSSを読み込み、`array( 'piyoko-style' )` で親CSSへの依存を指定
- `add_action` の優先度を `20` にし、親テーマより後に読み込まれるよう制御

親テーマを更新しても変更が失われない構成です。

### 3. ブロックエディタ対応（theme.json）

編集画面で使える選択肢をテーマ側から制御し、運用時にデザインが崩れないようにしました。

- カラーパレット6色、フォントサイズ4段階、余白5段階を定義
- `defaultPalette: false` でWordPress既定の色（青・赤・紫など）を無効化
- `custom: false` でカラーピッカーを無効化し、**定義した色以外を選べないように**
- `contentSize: 720px` / `wideSize: 1120px` でレイアウト幅を指定
- `add_editor_style()` でエディタにもテーマCSSを適用し、編集画面と実表示を一致させた

### 4. カスタム投稿タイプ「メニュー」

メニューをHTMLの直書きから、管理画面で編集できるデータに移行しました。

| 項目 | 実装 |
|---|---|
| 投稿タイプ | `register_post_type( 'menu' )` |
| タクソノミー | `register_taxonomy( 'menu_category' )`（サンドイッチ / プレート / スイーツ / ドリンク） |
| 価格 | カスタムフィールド（`add_meta_box` + `update_post_meta`） |

カスタムフィールドの保存処理では、以下の3点を実装しています。

```php
// 自動保存では処理しない（空データの上書き防止）
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

// nonce検証（CSRF対策）
if ( ! wp_verify_nonce( $_POST['piyoko_price_nonce'], 'piyoko_save_price' ) ) { return; }

// 権限チェック
if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
```

値は `absint()` でサニタイズしてから保存。メタキーは `_piyoko_price` と先頭にアンダースコアを付け、管理画面の「カスタムフィールド」欄に重複表示されないようにしています。

---

## 技術選定の理由

### なぜナビをハードコーディングしないか

`wp_nav_menu()` で管理画面から編集できるようにしました。納品後にページが増えた際、コードを触らずにクライアント側でナビを更新できます。制作会社の案件では運用フェーズが長く続くため、この設計を優先しました。

### なぜメニューをカスタム投稿タイプにするか

価格や商品説明は変更頻度が高い情報です。HTMLに直書きすると、価格改定のたびに開発者の作業が発生します。カスタム投稿タイプ + カスタムフィールドにすることで、店舗側で完結できる運用にしました。

### なぜ theme.json で選択肢を絞るか


ブロックエディタは自由度が高い反面、クライアントが自由に色を選べる状態だとデザインが崩れます。パレットを6色に固定し、カラーピッカーを無効化することで、**誰が編集してもトンマナが保たれる**状態を作りました。デザインシステムをコードで担保する考え方です。

### なぜ投稿タイプのスラッグを `menu-item` にしたか

固定ページのスラッグ `menu` と衝突し、どちらかが表示されなくなるためです。URLの重複はWordPressで頻出する不具合なので、設計段階で回避しました。

---

## 苦労した点・工夫した点

### ① 画像が重くアップロードできなかった

生成した画像5枚が合計11.6MBあり、WordPressのアップロード上限（2MB）を超えてアップロードできませんでした。上限を引き上げる対処も可能でしたが、Webサイトとして過大なサイズであることが根本の問題だと判断し、**画像側を最適化する方針**を取りました。

`sips` で長辺1600px・品質80のJPEGに変換し、**11.6MB → 2.1MB（約82%削減）** を達成。見た目の劣化はほぼありません。

### ② テンプレート階層の理解が曖昧で表示が切り替わらなかった

お知らせ一覧を `archive.php` として作成しましたが、意図した表示になりませんでした。調べた結果、「投稿ページ」に指定した固定ページには `archive.php` ではなく **`home.php`** が使われる仕様であることが分かり、修正しました。

さらに、「投稿ページ」だけを設定した状態ではトップページがブログ一覧と判定され、`front-page.php` が使われない挙動にも遭遇しました。ホームページ用の固定ページを別途作成することで解決しています。

テンプレート階層は図で覚えるだけでなく、実際に切り替わらない状況を経験したことで理解が定着しました。

### ③ 画像の有無でカードの高さが崩れた

トップページのメニューをカスタム投稿から取得したところ、アイキャッチ画像を設定していない商品でカードの高さが揃わなくなりました。

対処として、画像未設定時に表示するプレースホルダー画像を用意し、さらにカード内を Flexbox で組んで `margin-top: auto` で価格を下端に固定。**説明文の長さが違ってもレイアウトが崩れない**構造にしました。

### ④ ファイルの配置ミスでテンプレートが認識されない


新規作成したテンプレートファイルが1階層上に置かれており、WordPressに認識されないことが複数回ありました。ファイル名のタイプミス（`single-manu.php`）でも同様の症状が出ます。

WordPressはファイル名と配置場所でテンプレートを判別するため、「画面が変わらない = ファイルが読まれていない」と切り分ける習慣がつきました。作成後に `ls` で確認する運用に変えています。

---

## 開発プロセス

`feature/xxx` ブランチを切り、Pull Request を経由して `main` にマージする運用で開発しました。

```
feature/sub-pages         下層ページの静的HTML
feature/theme             テーマ化（header/footer分割、front-page.php）
feature/custom-post-type  カスタム投稿タイプ・タクソノミー・カスタムフィールド
feature/theme-json        ブロックエディタのデザイントークン制御
feature/child-theme       子テーマ
feature/news-templates    お知らせ一覧・詳細
feature/front-page-cpt    トップページのメニューをCPT化
```

PRには変更内容・意図・今後の課題を記載しています。実際の履歴は [Pull Requests](https://github.com/takatoseki0107/piyoko-cafe/pulls?q=is%3Apr+is%3Aclosed) から確認できます。

---

## ディレクトリ構成

```
piyoko-cafe/
├── docker-compose.yml           # WordPress + MySQL
├── static/                      # テーマ化前の静的HTML
│   ├── index.html
│   ├── menu.html / about.html / news.html / access.html
│   ├── css/style.css
│   └── images/
└── themes/
    ├── cafe-theme/              # 親テーマ
    │   ├── assets/
    │   │   ├── css/style.css
    │   │   ├── js/main.js
    │   │   └── images/
    │   ├── header.php
    │   ├── footer.php
    │   ├── front-page.php       # トップページ
    │   ├── page.php             # 固定ページ
    │   ├── home.php             # お知らせ一覧
    │   ├── single.php           # お知らせ詳細
    │   ├── archive-menu.php     # メニュー一覧
    │   ├── single-menu.php      # メニュー詳細
    │   ├── index.php
    │   ├── functions.php
    │   ├── style.css
    │   └── theme.json
    └── cafe-theme-sakura/       # 子テーマ
        ├── style.css
        └── functions.php
```

---

## ローカル環境の起動

### 前提条件

- Docker Desktop

### 手順

```bash
git clone https://github.com/takatoseki0107/piyoko-cafe.git
cd piyoko-cafe
docker compose up -d
```

ブラウザで `http://localhost:8000` を開き、WordPressの初期セットアップを行ってください。

セットアップ後、以下の設定が必要です。

1. **外観 → テーマ** で「Piyoko Cafe」を有効化
2. **設定 → パーマリンク** で「投稿名」を選択して保存
3. **固定ページ** を作成（スラッグ: `home` / `about` / `menu` / `news` / `access`）
4. **設定 → 表示設定** で「ホームページ」に `ホーム`、「投稿ページ」に `お知らせ` を指定
5. **外観 → メニュー** で「ヘッダーナビ」を作成し、表示位置に設定

子テーマを試す場合は、**外観 → テーマ** で「ぴよこcafe 桜」を有効化してください。配色が春仕様に切り替わります。

---

## アクセシビリティ・パフォーマンス

- 全画像に `alt` 属性を設定
- ハンバーガーメニューに `aria-label` / `aria-expanded` を付与し、開閉状態を支援技術に伝達
- パンくずリストを `<ol>` + `aria-current="page"` でマークアップ
- 画像に `loading="lazy"` を指定
- アイキャッチは用途に応じたサイズを指定（トップページは `medium_large`）
- 画像を約82%圧縮（11.6MB → 2.1MB）

---

## 今後の課題

- 画像のWebP化と Lighthouse によるスコア計測
- Googleマップの埋め込み（現在はプレースホルダー）
- お問い合わせフォームの実装
- 本番環境へのデプロイ

---

## その他の制作物

| プロジェクト | 概要 |
|---|---|
| [Aibo](https://github.com/takatoseki0107/aibo) | AIが家計をそっと支える家計管理アプリ（Python / AWS Lambda / Bedrock / Terraform） |
| [NudgeMe](https://github.com/takatoseki0107/nudge-me) | 優柔不断な人をAIがナッジして意思決定を助けるWebアプリ（Go / Next.js / DynamoDB） |