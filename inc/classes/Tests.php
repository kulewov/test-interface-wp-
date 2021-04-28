<?php

namespace SoV;

use SoV\Tests\TestInterface;

class Tests
{
    const NAMESPACE = '\SoV\Tests';
    const MAIN_GET_PARAM = 'sovTest';

    public function __construct()
    {
        add_action('init', function() {
            $method = $_GET[self::MAIN_GET_PARAM] ?? '';

            if (!$method) {
                return;
            }

            if ($method === 'menu') {
                $this->drawMenu();
            } else {
                $this->checkTest($method);
            }
        });
    }

    private function isAccess(TestInterface $test): bool
    {
        $caps = $test->getCapabilities();
        foreach ($caps as $cap) {
            if ($cap === 'all' || current_user_can($cap)) {
                return true;
            }
        }

        return false;
    }

    public function drawMenu()
    {
        $excludeFiles = [
            'TestInterface.php',
        ];
        $path = __DIR__ . '/Tests/';
        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($path . $file) && !in_array($file, $excludeFiles)) {
                    $this->drawMenuItem($file);
                }
            }
            closedir($dh);
        }
        die();
    }

    private function drawMenuItem($fileName)
    {
        $name = preg_replace('|\.php|', '', $fileName);
        $class = self::NAMESPACE . '\\' . $name ;
        if (class_exists($class)) {
            $test = new $class;
            if ($this->isAccess($test)) { ?>
                <div>
                    <a href="?<?= self::MAIN_GET_PARAM ?>=<?= $name ?>">
                        <?= $test->getName() ?>
                    </a>
                </div>
                <div>
                    <?= $test->getDescription() ?>
                </div>
                <br/>
            <?php }
        }
    }

    private function checkTest($method)
    {
        $class = self::NAMESPACE . '\\' . $method ;
        if (class_exists($class)) {
            $test = new $class;
            $this->isAccess($test) && $this->execTest($test);
        } else {
            die('Что то пошло не так!');
        }
    }

    private function execTest(TestInterface $test) {
        $exec = $_GET['exec'] ?? false;
        if ($test->getMenu() && !$exec) {
            $this->drawSubMenu($test);
        } else {
            $test->exec();
        }
    }
    private function drawSubMenu(TestInterface $test)
    {
        echo '<h1>' . $test->getName() . '</h1>';
        foreach ($test->getMenu() as $params => $description) { ?>
            <div>
                <a href="?<?= self::MAIN_GET_PARAM ?>=<?= substr(strrchr(get_class($test), "\\"), 1); ?>&exec=1&<?= $params ?>" target="_blank">
                    <?= $description ?>
                </a>
            </div>
        <?php }
        die();
    }
}
