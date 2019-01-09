<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <?php /* source: https://realfavicongenerator.net */ ?>
        <link rel="apple-touch-icon" sizes="180x180" href="/static/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/static/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/static/icons/favicon-16x16.png">
        <link rel="manifest" href="/static/icons/site.webmanifest">
        <link rel="mask-icon" href="/static/icons/safari-pinned-tab.svg" color="#282828">
        <link rel="shortcut icon" href="/static/icons/favicon.ico">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="msapplication-config" content="/static/icons/browserconfig.xml">
        <meta name="theme-color" content="#282828">

        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Share:400,400i,700,700i&amp;subset=latin-ext">
        <link rel="stylesheet" href="/static/all.css">
        <title>Journbers</title>
        <script>
            'use strict';
            document.addEventListener('DOMContentLoaded', () => {
                let first = document.querySelector('[tabindex="1"]')
                first.focus()
            })
        </script>
    </head>
    <body>
        <div id="Wrapper">
            <header>
                <img src="/static/logo.svg" alt="Journbers logo">
                <h1>Journbers</h1>
            </header>
            <section id="Login">
                <form action="/login" method="post">
                    <label for="User">User</label>
                    <input type="text" id="User" name="User" placeholder="Ranulph" tabindex="1">
                    <label for="Pass">Password</label>
                    <input type="password" id="Pass" name="Pass" placeholder="**********" tabindex="2">
                    <input type="submit" value="Login and continue&hellip;" tabindex="3">
                </form>
            </section>
        </div>
    </body>
</html>

