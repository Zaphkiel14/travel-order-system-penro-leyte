<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\ErrorHandler;
use App\Models\SelectModel;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */




abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */

    protected array $globalData = [];
    protected ErrorHandler $errorHandler;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        $this->errorHandler = new ErrorHandler();
        
        helper('dashboard');
        $this->loadGlobalData();
    }

    protected function renderError(array $error)
    {
        return response()
            ->setStatusCode($error['error_code'])
            ->setBody(view('error_page', $error));
    }

    protected function loadGlobalData()
    {
        $role   = session()->get('role');
        $userId = session()->get('user_id');

        if (!$role || !$userId) {
            $this->globalData['pendingSummary'] = null;
            return;
        }

        $model = new SelectModel();

        $this->globalData['pendingSummary'] = $model->getUserPendingSummary($userId, $role);
    }
}
