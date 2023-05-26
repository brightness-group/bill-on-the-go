<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class CoreHelpers
{
    /**
     * Get replaced file url.
     *
     * @param $pathWithFile
     * @param $replacePath
     * @return string|null
     */
    public static function getFileUrl($pathWithFile, $replacePath): string|null
    {
        $tempPathArr = explode('/', $pathWithFile);
        if (count($tempPathArr) > 1) {
            $fileName = Arr::last($tempPathArr);
            Arr::forget($tempPathArr, count($tempPathArr) - 1);
            return implode('/', $tempPathArr) . '/' . $replacePath . '/' . $fileName;
        }
        return $pathWithFile;
    }

    /**
     * Get all saved pages from cookies.
     *
     * @return array
     */
    public static function getAllPages(): array
    {
        $allPages = Cookie::get('saved_pages');
        if (!$allPages) {
            return [];
        }
        return json_decode($allPages,true);
    }

    /**
     * Get previous state by page and key.
     *
     * @param string $page
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public static function getPreviousState(string $page, string $key, string $default): mixed
    {
        $pages = self::getAllPages();
        return !empty($pages[$page][$key]) ? $pages[$page][$key] : $default;
    }

    /**
     * Set single state in one shot.
     *
     * @param string $page
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function setState(string $page, string $key, string $value): void
    {
        $pages = self::getAllPages();
        $pages[$page][$key] = $value;
        Cookie::queue('saved_pages', json_encode($pages));
    }

    /**
     * Set multiple states in one shot.
     *
     * @param string $page
     * @param array $data
     * @return void
     */
    public static function setMultipleState(string $page, array $data): void
    {
        $pages = self::getAllPages();
        foreach ($data as $key => $val) {
            $pages[$page][$key] = $val;
        }
        Cookie::queue('saved_pages', json_encode($pages));
    }

    /**
     * Get difference for tokens.
     *
     * @param object|null $model
     * @return \DateInterval|null
     */
    public static function calculateDiffTimes(?object $model)
    {
        if (!is_null($model)) {
            $startTime = Carbon::parse($model->created_at);
            $endTime = Carbon::now();
            return $startTime->diff($endTime);
        }
        return null;
    }
}
