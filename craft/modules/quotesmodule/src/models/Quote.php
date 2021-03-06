<?php
/**
 * Quotes module for Craft CMS 3.x
 *
 * create a pro-forma quote using a form
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2019 Kurious Agency
 */

namespace modules\quotesmodule\models;

use modules\quotesmodule\QuotesModule;

use Craft;
use craft\base\Model;

/**
 * QuotesModuleModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Kurious Agency
 * @package   QuotesModule
 * @since     1.0.0
 */
class Quote extends Model
{
    // Public Properties
    // =========================================================================

    public $id;
    public $quoter;
    public $approver;
    public $customerName;
    public $customerCompany;
    public $customerEmail;
    public $customerAddress;
    public $summary;
    public $products;
    public $discountedTotal;
    public $discount;
    public $subtotal;
    public $tax;
    public $total;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
