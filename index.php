<!doctype html>
<?php require_once('inc/head.php') ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Index of <?= $URI ?></title>

    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />

    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="/favicon.svg">
    <link rel="mask-icon" href="/favicon.svg" color="#000000">
    <link rel="apple-touch-icon" href="/favicon.svg">
</head>
<body>
  <div class="min-h-screen bg-gray-100 py-6 justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-4xl sm:mx-auto">
      <div class="relative bg-white shadow-lg sm:rounded-xl">
        <div class="flex space-x-2 px-6 pt-6 pb-2">
          <?= breadcrumb() ?>
        </div>
        <div class="flex px-6 pb-6 pt-2">
          <ul class="list-disc space-y-1">
            <?= listing() ?>
          </ul>
        </div>
      </div>
  </div>
</body>
</html>
