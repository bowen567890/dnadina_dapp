<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- 其他 head 内容 -->
</head>
<body>
<div id="app">
    <websocket-test></websocket-test>
</div>

<!-- 引入前端资源 -->
@vite(['resources/js/app.js'])
</body>
</html>
