<?php
/**
 * Quotes module for Craft CMS 3.x
 *
 * create a pro-forma quote using a form
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2019 Kurious Agency
 */

namespace modules\quotesmodule\services;

use modules\quotesmodule\QuotesModule;
use modules\quotesmodule\models\Quote;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\elements\GlobalSet;

/**
 * QuotesModuleService Service
 *
 * All of your module’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other modules can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Kurious Agency
 * @package   QuotesModule
 * @since     1.0.0
 */
class QuotesModuleService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin/module file, call it like this:
     *
     *     QuotesModule::$instance->quotesModuleService->exampleService()
     *
     * @return mixed
     */
    public function calculateQuote(Quote $quote)
    {
        $prodLookUp = Entry::find()
            ->section('products');
        $hoursPerDay = GlobalSet::find()
            ->handle('hoursPerDay')
            ->one();
        $hoursPerDay = $hoursPerDay->number;
        $tax = GlobalSet::find()
            ->handle('tax')
            ->one();
        
        $quote->subtotal = 0;
        foreach ($quote->products as $key => $model) {
            $product = $prodLookUp->slug($key)->one();
            $model->name = $product->title;
            $category = $product->productType->one();
            switch ($model->units) {

                case 'd':
                    if ($model->amount == 1){
                        $model->units = 'day';
                    } else {
                        $model->units = 'days';
                    }
                    $model->pricePerUnit = $category->price * $hoursPerDay;
                    $model->productTotal = $model->pricePerUnit * $model->amount;
                    break;
                case 'h':
                if ($model->amount == 1){
                    $model->units = 'hour';
                } else {
                    $model->units = 'hours';
                }
                    $model->pricePerUnit = $category->price;
                    $model->productTotal = $category->price * $model->amount;
                    break;
                default:
                    $model->pricePerUnit = $category->price;
                    $model->productTotal = $category->price * $model->amount;
                    break;
            }
            $quote->subtotal = $quote->subtotal + $model->productTotal;
        }
        if (!is_null($quote->discount)) {
            $quote->discountedTotal = $quote->subtotal * (1-($quote->discount / 100));
        } else {
            $quote->discountedTotal = $quote->subtotal;
        }
        $quote->tax = ($tax->number/100) * $quote->discountedTotal;
        $quote->total = $quote->discountedTotal + $quote->tax;
        
        return $quote;
    }
}
