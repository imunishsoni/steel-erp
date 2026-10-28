<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'erp'];

    /**
     * Session instance.
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Current authenticated user data.
     */
    protected $currentUser;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload session & current user
        $this->session = service('session');
        $this->currentUser = $this->session->get('user');
    }

    /**
     * Standard view renderer with layout and user context
     */
    protected function render(string $view, array $data = [], string $layout = 'layouts/main'): string
    {
        $data['currentUser'] = $this->currentUser;
        $data['company']     = company_settings();
        $data['currentUri']   = (string)$this->request->getUri();
        $data['mainContent'] = view($view, $data);

        return view($layout, $data);
    }
}
