<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>積読 - ブクログ初級</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- ヘッダー部分 -->
    <header class="bg-blue-600 text-white p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">ブクログ</h1>
            <!-- メニューバー -->
            <nav class="hidden md:flex space-x-6">
                <a href="index.php" class="hover:text-blue-200 transition-colors font-medium">検索</a>
                <a href="read.php" class="hover:text-blue-200 transition-colors font-medium">読了済み</a>
                <a href="tsundoku.php" class="text-blue-200 font-medium border-b-2 border-blue-200">積読</a>
            </nav>
            <!-- モバイルメニューボタン -->
            <button id="mobile-menu-btn" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <!-- モバイルメニュー -->
        <nav id="mobile-menu" class="md:hidden mt-4 hidden">
            <div class="flex flex-col space-y-2">
                <a href="index.php" class="hover:text-blue-200 transition-colors font-medium py-2">検索</a>
                <a href="read.php" class="hover:text-blue-200 transition-colors font-medium py-2">読了済み</a>
                <a href="tsundoku.php" class="text-blue-200 font-medium py-2">積読</a>
            </div>
        </nav>
    </header>

    <!-- ページタイトル -->
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">積読の本</h2>
    </div>

    <!-- 積読書籍一覧 -->
    <div class="result">
        <div class="text-center p-4">
            <p class="text-gray-600">積読の本を読み込み中...</p>
        </div>
    </div>

    <!-- jQueryライブラリ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- JavaScriptファイル -->
    <script src="js/tsundoku.js"></script>
    <script>
        // モバイルメニューの切り替え
        $(document).ready(function() {
            $('#mobile-menu-btn').click(function() {
                $('#mobile-menu').toggleClass('hidden');
            });
        });
    </script>

    <!-- コメント入力モーダル -->
    <div id="comment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">コメントを追加</h3>
                <div class="mb-4">
                    <label for="book-comment" class="block text-sm font-medium text-gray-700 mb-2">
                        コメント（任意）
                    </label>
                    <textarea 
                        id="book-comment" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="この本についてのコメントを入力してください..."
                    ></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button 
                        id="cancel-comment" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                    >
                        キャンセル
                    </button>
                    <button 
                        id="save-comment" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
                    >
                        保存
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
