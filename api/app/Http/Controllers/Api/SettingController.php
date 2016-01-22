<?php namespace App\Http\Controllers\Api;

use Input;
use Validator;
use Response;

use App\Models\Setting;
use App\Transformers\SettingTransformer;

class SettingController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = new Setting;
        $settings = $settings->simplePaginate(Input::get('limit', 50));

        return response()->paginator($settings, new SettingTransformer);
    }

    public function findByName($name) {
        $setting = Setting::where('name', '=', $name)->first();
        $this->checkExist($setting);

        return $setting;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
