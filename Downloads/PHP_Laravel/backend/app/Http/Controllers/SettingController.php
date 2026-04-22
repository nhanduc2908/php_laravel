<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return $this->success($settings, 'Settings retrieved');
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return $this->success(null, 'Settings updated');
    }

    public function byGroup($group)
    {
        $settings = Setting::where('group', $group)->get();
        return $this->success($settings, "Settings for group: {$group}");
    }

    public function reset($group)
    {
        Setting::where('group', $group)->delete();
        return $this->success(null, 'Settings reset');
    }
}