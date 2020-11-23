<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сайт Артёма Фомина</title>
    <link rel="stylesheet" href="/static/css/style.css" type="text/css">
</head>
<body>
    <header>
        <h1>Сайт Артёма Фомина</h1>
        <div class="breadcrumbs">
            <span>Навигация:</span>
            <span class="breadcrumbs-current">Главная страница</span>
        </div>
    </header>

    <main>
        <h2>Главная страница</h2>
        <?php
        require("globals.php");

        class LabWork {
            public int $labWorkID;
            public array $childSectionIDs;
            
            function __construct($labWorkID) {
                $this->labWorkID = $labWorkID;
                $this->childSectionIDs = [];
            }

            function add_section($sectionName) {
                $sectionID = (int)rtrim($sectionName, ".html");
                array_push($this->childSectionIDs, $sectionID);
            }

            function sort_sections() {
                sort($this->childSectionIDs);
            }
        }

        class Discipline {
            public string $disciplineID;
            public string $disciplineHumanName;
            public array $childLabWorks;
            
            function __construct($disciplineID, $disciplineHumanName) {
                $this->disciplineID = $disciplineID;
                $this->disciplineHumanName = $disciplineHumanName;
                $this->childLabWorks = [];
            }

            function add_lab_work($labWorkName) {
                $labWorkID = (int)substr($labWorkName, 2);
                $labWork = new LabWork($labWorkID);
                array_push($this->childLabWorks, $labWork);
                return $labWork;
            }

            function lab_work_compare_func(LabWork $a, LabWork $b) {
                return $a->labWorkID - $b->labWorkID;
            }

            function sort_lab_works() {
                usort($this->childLabWorks, ["Discipline", "lab_work_compare_func"]);
            }
        }

        $disciplines = [];

        chdir("iframes");
        $disciplineDirs = glob("*" , GLOB_ONLYDIR);
        foreach ($disciplineDirs as $disciplineDir) {
            $discipline = new Discipline($disciplineDir, get_disc_human_name($disciplineDir));
            array_push($disciplines, $discipline);
            
            chdir($disciplineDir);
            $labWorkDirs = glob("*" , GLOB_ONLYDIR);
            foreach ($labWorkDirs as $labWorkDir) {
                $labWork = $discipline->add_lab_work($labWorkDir);

                chdir($labWorkDir);
                $sectionFiles = glob("*.html");
                foreach ($sectionFiles as $sectionFile) {
                    $labWork->add_section($sectionFile);
                }
                $labWork->sort_sections();
                chdir("..");
            }
            $discipline->sort_lab_works();
            chdir("..");
        }
        chdir("..");

        foreach ($disciplines as $discipline) {
            echo "<button class='discipline-group-button group-" . $discipline->disciplineID . "'>" . $discipline->disciplineHumanName . "</button>";
            echo "<div class='discipline-group group-" . $discipline->disciplineID . "'><div class='discipline-group-content'>";
            foreach ($discipline->childLabWorks as $labWork) {
                echo "<h4>Лабораторная работа " . $labWork->labWorkID . "</h4>";
                foreach ($labWork->childSectionIDs as $sectionID) {
                    echo "<p><a href='/view.php?disc=" . $discipline->disciplineID . "&lr=" . $labWork->labWorkID . "&sect=" . $sectionID . "'>Раздел " . $sectionID . "</a></p>";
                }
            }
            echo "</div></div>";
        }
        ?>
    </main>

    <footer>(c) 2020 Артём Фомин и Московский Политех</footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script>
        function revealGroup(event) {
            $(".discipline-group-button.group-" + event.data.disciplineID).addClass("revealed");
            $(".discipline-group.group-" + event.data.disciplineID).css("max-height", ($(".discipline-group.group-" + event.data.disciplineID).contents().height() + 8) + "px");
            $(".discipline-group-button.group-" + event.data.disciplineID).one("click", {disciplineID: event.data.disciplineID}, collapseGroup);
        }

        function collapseGroup(event) {
            $(".discipline-group-button.group-" + event.data.disciplineID).removeClass("revealed");
            $(".discipline-group.group-" + event.data.disciplineID).css("max-height", "0px");
            $(".discipline-group-button.group-" + event.data.disciplineID).one("click", {disciplineID: event.data.disciplineID}, revealGroup);
        }
        
        <?php
        foreach ($disciplines as $discipline) {
            echo "$('.discipline-group-button.group-" . $discipline->disciplineID . "').one('click', {disciplineID: '" . $discipline->disciplineID . "'}, revealGroup);\n";
        }
        ?>
    </script>
</body>
</html>