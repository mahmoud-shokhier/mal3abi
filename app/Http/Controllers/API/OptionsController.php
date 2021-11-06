<?php
/**
 * Created by PhpStorm.
 * User: AQSSA
 */

namespace App\Http\Controllers\API;


use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionsController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->get('key') && $request->get('value') !== null) {
            Utilities::updateSetting($request->get('key'), ['value' => $request->get('key')]);
        }

        return DB::table('options')->get();
    }
}
