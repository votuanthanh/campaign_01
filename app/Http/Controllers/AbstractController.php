<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Activity;

abstract class AbstractController extends Controller
{
    protected $repositoryName;

    protected $user;

    protected $compacts;

    protected $view;

    protected $repository;

    protected $dataSelect = ['*'];

    protected $lang = [
        'prefix' => 'repositories.',
        'replacements' => [],
    ];

    public function __construct($repository = null)
    {
        if ($repository) {
            $this->repositorySetup($repository);
        }

        $this->user = Auth::guard($this->getGuard())->user();
    }

    public function repositorySetup($repository = null)
    {
        $this->repository = $repository;

        $this->repositoryName = strtolower(class_basename($this->repository->getModel()));
    }

    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }

    public function activityLog($actions, $message = null)
    {
        Activity::log( $message ?: $this->repositoryName . ':' . $actions );
    }

    public function viewRender($data = [], $view = null)
    {
        $view = $view ?: $this->view;

        $compacts = array_merge($data, $this->compacts);

        return view($this->viewPrefix . $view, $compacts);
    }

    public function trans($str = null, $data = [])
    {
        $replacements = array_merge($data, $this->lang['replacements']);

        return trans($this->lang['prefix'] . $str, $replacements);
    }
}
