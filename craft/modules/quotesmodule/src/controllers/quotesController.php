<?php
/**
 * Quotes module for Craft CMS 3.x
 *
 * create a pro-forma quote using a form
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2019 Kurious Agency
 */

namespace modules\quotesmodule\controllers;

use modules\quotesmodule\QuotesModule;
use modules\quotesmodule\models\QuoteProduct;
use modules\quotesmodule\models\Quote;


use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Kurious Agency
 * @package   QuotesModule
 * @since     1.0.0
 */
class QuotesController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/quotes-module/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our module's actionDoSomething URL,
     * e.g.: actions/quotes-module/qutoes/create-draft
     *
     * @return mixed
     */
    public function actionCreateDraft()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $quote = new Quote;
        $quote->quoter = $request->getBodyParam('quoter');
        $quote->approver = $request->getBodyParam('approver');
        $quote->customerName = $request->getBodyParam('customer-name');
        $quote->customerCompany = $request->getBodyParam('customer-company');
        $quote->customerEmail = $request->getBodyParam('customer-email');
        $quote->customerAddress = $request->getBodyParam('customer-address');
        $quote->summary = $request->getBodyParam('summary');
        $quote->discount = $request->getBodyParam('discount');
        $productIds = $request->getBodyParam('added');
        foreach ($productIds as $key => $value) {
            $product = new QuoteProduct;
            $product->amount = $request->getBodyParam('amount-'.$key);
            $product->units = $request->getBodyParam('units-'.$key);
            $product->ref = $key;
            $quote->products[$key] = $product;
        }
        $variables['quote'] = QuotesModule::$instance->quotesModuleService->calculateQuote($quote);

        return $this->renderTemplate('/step2',$variables);
    }
}
