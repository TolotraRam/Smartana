<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use App\Transformers\SettingTransformer;
use Input;
use Response;

class SettingController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = new Setting();
        $settings = $settings->simplePaginate(Input::get('limit', 50));

        return response()->paginator($settings, new SettingTransformer());
    }

    public function findByName($name)
    {
        $setting = Setting::where('name', '=', $name)->paginate(15);
        $this->checkExist($setting);

        return response()->paginator($setting, new SettingTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        foreach (Input::all() as $fields) {
            $setting = Setting::where('name', '=', $fields['name'])->first();
            $setting->value = $fields['value'];
            $setting->save();
        }
        $settings = new Setting();
        $settings = $settings->simplePaginate(Input::get('limit', 50));

        return response()->paginator($settings, new SettingTransformer());
        //return $input;
    }
}
