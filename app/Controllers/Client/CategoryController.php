<?php

namespace App\Controllers\Client;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Helpers\CategoryHelper;
use App\Helpers\TourHelper;
use App\Models\Category;
use App\Models\City;
use App\Models\Tour;

class CategoryController
{
    // Danh sach ID cac thanh pho noi dia (tu ID 1 den 13)
    private const DOMESTIC_CITY_IDS = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
    // Thanh pho co san bay quoc te (Ha Noi, TP.HCM, Da Nang)
    private const AIRPORT_CITY_IDS = [1, 2, 3];

    private function getRootSlug(array $categoryDetail, array &$allCategories): string
    {
        if (empty($categoryDetail['parent'])) {
            return $categoryDetail['slug'];
        }
        foreach ($allCategories as $cat) {
            if ($cat['id'] == $categoryDetail['parent'] && empty($cat['parent'])) {
                return $cat['slug'];
            }
        }
        return $categoryDetail['slug'];
    }

    public function list(Request $request): void
    {
        $categoryDetail = Category::findOne([
            'slug' => $request->params['slug'],
            'deleted' => false,
            'status' => 'active',
        ]);
        if (!$categoryDetail) {
            Response::redirect('/');
            return;
        }

        $breadcrumb = [];
        if (!empty($categoryDetail['parent'])) {
            $breadcrumb = CategoryHelper::getCategoryParent($categoryDetail['parent']);
        }
        $breadcrumb[] = [
            'id' => $categoryDetail['id'],
            'name' => $categoryDetail['name'],
            'avatar' => $categoryDetail['avatar'],
            'slug' => $categoryDetail['slug'],
        ];

        $child = CategoryHelper::getCategoryChild($categoryDetail['id']);
        $ids = array_merge([$categoryDetail['id']], array_column($child, 'id'));
        $query = $request->query();
        $query['categoryIds'] = $ids;
        $tourList = TourHelper::enrichTourList(Tour::search($query));

        // Xac dinh danh sach thanh pho hien thi trong bo loc
        $allCategories = Category::find(['deleted' => false, 'status' => 'active']);
        $rootSlug = $this->getRootSlug($categoryDetail, $allCategories);
        $allCities = City::find([]);

        if ($rootSlug === 'tour-nuoc-ngoai') {
            // Tour nuoc ngoai: khoi hanh tu 3 thanh pho co san bay; diem den la quoc te
            $cityListFrom = array_values(array_filter($allCities, fn($c) => in_array((int)$c['id'], self::AIRPORT_CITY_IDS)));
            $cityListTo   = array_values(array_filter($allCities, fn($c) => !in_array((int)$c['id'], self::DOMESTIC_CITY_IDS)));
        } else {
            // Tour noi dia: ca diem di va diem den deu la thanh pho noi dia
            $cityListFrom = array_values(array_filter($allCities, fn($c) => in_array((int)$c['id'], self::DOMESTIC_CITY_IDS)));
            $cityListTo   = $cityListFrom;
        }

        View::render('client/pages/tour-list', [
            'pageTitle'      => 'Danh sách tour',
            'categoryDetail' => $categoryDetail,
            'breadcrumb'     => $breadcrumb,
            'tourList'       => $tourList,
            'cityListFrom'   => $cityListFrom,
            'cityListTo'     => $cityListTo,
        ]);
    }
}
