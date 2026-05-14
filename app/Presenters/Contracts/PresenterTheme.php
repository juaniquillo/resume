<?php

namespace App\Presenters\Contracts;

interface PresenterTheme
{
    /**
     * @return array<string, string|string[]>
     */
    public function containerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function nameThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function labelThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function sectionThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function sectionTitleThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function itemTitleThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function itemContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function itemDetailsThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function summaryThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function contactContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function contactItemThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function listThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function imageThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function linkThemes(): array;
}
