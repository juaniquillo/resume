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
    public function basicsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function summaryContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function workContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function volunteersContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function educationContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function awardsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function certificatesContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function publicationsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function skillsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function languagesContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function interestsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function referencesContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function projectsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function downloadsContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function coverLetterContainerThemes(): array;

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
    public function imageContainerThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function imageThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function linkThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function iconThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function listItemThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function badgeThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function keywordBadgeThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function socialBadgeThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function dateThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function subTitleThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function emailThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function phoneThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function urlThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function locationThemes(): array;

    /**
     * @return array<string, string|string[]>
     */
    public function profileThemes(): array;

    /**
     * @return array<int, string>
     */
    public function fontUrls(): array;

    /**
     * @return array<int, array{family: string, path: string, weight?: string, style?: string, type?: string}>
     */
    public function localFonts(): array;

    public function fontFamily(): string;
}



