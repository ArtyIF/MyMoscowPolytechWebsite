<!DOCTYPE html>
<html lang="ru" class="fullscreen">
<head>
    <meta charset="UTF-8">
    <title>
    <?php
    require("globals.php");
    echo get_disc_human_name($_GET['disc']) . ", лабораторная работа " . $_GET['lr'] . ", раздел " . $_GET['sect'] . " - Сайт Артёма Фомина";
    ?>
    </title>
    <link rel="stylesheet" href="/static/css/style.css" type="text/css">
    <link href="/static/css/prism.css" rel="stylesheet" />
</head>
<body>
    <header class="header-itself">
        <h1><button class="header-button-left" id="homePageButton">&lt; Главная страница</button> Сайт Артёма Фомина <button class="header-button-right" id="headerHideButton">Скрыть шапку</button> <button class="header-button-right" id="codeToggleButton">Показать код</button></h1>
        <div class="header-page-title">
            <?php
                $disciplines = get_all_lab_works();
                $labWorksShort = [];
                foreach ($disciplines as $discipline) {
                    foreach ($discipline->childLabWorks as $labWork) {
                        foreach ($labWork->childSectionIDs as $sectionID) {
                            array_push($labWorksShort, [$discipline->$disciplineID, $labWork->$labWorkID, $sectionID]);
                        }
                    }
                }
                $currentLabWorkIndex = array_search([$_GET['disc'], (int)$_GET['lr'], (int)$_GET['sect']], $labWorksShort);
                $previousLabWorkIndex = $currentLabWorkIndex - 1;
                if ($previousLabWorkIndex < 0) {
                    $previousLabWorkIndex = count($labWorksShort) - 1;
                }
                $nextLabWorkIndex = $currentLabWorkIndex + 1;
                if ($nextLabWorkIndex >= count($labWorksShort)) {
                    $nextLabWorkIndex = 0;
                }
                echo "<a class='header-previous-section' href='/view.php?disc=" . $labWorksShort[$previousLabWorkIndex][0] . "&lr=" . $labWorksShort[$previousLabWorkIndex][1] . "&sect=" . $labWorksShort[$previousLabWorkIndex][2] . "'>&lt; Предыдущая работа</a>";
            ?>
            <span class="header-page-title-itself"><?php echo get_disc_human_name($_GET['disc']) . ", лабораторная работа " . $_GET['lr'] . ", раздел " . $_GET['sect'] ?></span>
            <?php
                echo "<a class='header-next-section' href='/view.php?disc=" . $labWorksShort[$nextLabWorkIndex][0] . "&lr=" . $labWorksShort[$nextLabWorkIndex][1] . "&sect=" . $labWorksShort[$nextLabWorkIndex][2] . "'>Следующая работа &gt;</a>";
            ?>
        </div>
    </header>
    <button class="header-pin-button" id="headerShowButton">Показать шапку</button>
    <main>
        <?php echo "<iframe id='pageIframe' src='/iframes/" . $_GET['disc'] . "/lr" . $_GET['lr'] . "/" . $_GET['sect'] . ".html' id='iframe'></iframe>"; ?>
        <pre hidden id="iframePre" class="language-markup line-numbers match-braces" data-src=<?php echo "'/iframes/" . $_GET['disc'] . "/lr" . $_GET['lr'] . "/" . $_GET['sect'] . ".html'"; ?>></pre>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="/static/js/prism.js"></script>
    <script>
        function showHeader() {
            $(".header-itself").css("animation", "none");
            void $(".header-itself")[0].offsetWidth;
            $(".header-itself").css("animation", "0.15s ease 0.15s 1 normal both running headerShow");

            $("#headerShowButton").css("animation", "none");
            void $("#headerShowButton")[0].offsetWidth;
            $("#headerShowButton").css("animation", "0.15s ease 0s 1 reverse both running headerShow");
        }

        function hideHeader() {
            $(".header-itself").css("animation", "none");
            void $(".header-itself")[0].offsetWidth;
            $(".header-itself").css("animation", "0.15s ease 0s 1 reverse both running headerShow");

            $("#headerShowButton").css("animation", "none");
            void $("#headerShowButton")[0].offsetWidth;
            $("#headerShowButton").css("animation", "0.15s ease 0.15s 1 normal both running headerShow");
        }
        
        function showCode() {
            $("#pageIframe").attr("hidden", true);
            $("#iframePre").attr("hidden", false);

            $("#codeToggleButton").one("click", hideCode);
            $("#codeToggleButton").text("Скрыть код");

            hideHeader();
        }

        function hideCode() {
            $("#pageIframe").attr("hidden", false);
            $("#iframePre").attr("hidden", true);

            $("#codeToggleButton").one("click", showCode);
            $("#codeToggleButton").text("Показать код");
            
            hideHeader();
        }
        
        $("#headerShowButton").on("click", showHeader);
        $("#headerHideButton").on("click", hideHeader);
        $("#codeToggleButton").one("click", showCode);
        $("#homePageButton").on("click", () => { window.location.href = "/" });
    </script>
</body>
</html>