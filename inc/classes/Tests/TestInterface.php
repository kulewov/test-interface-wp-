<?php
namespace SoV\Tests;

interface TestInterface
{
    /**
     * Возвращает имя теста
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Возвращает описание теста
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Возращает массив ролей и возможностей для доступа к данному тесту.
     *
     * @return array
     */
    public function getCapabilities(): array;

    /**
     * Возвращает меню теста, если оно есть.
     *
     * @return array
     */
    public function getMenu(): array;

    /**
     * Выполняет тест
     */
    public function exec(): void;
}
