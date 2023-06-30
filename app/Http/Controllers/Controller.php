<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      description="Dibuat oleh Luthfi Nur Ramadhan ✨ - 2142430",
 *      version="1.0.0",
 *      title="UAS Laravel API Obat Documentation",
 *      @OA\Contact(
 *          email="luthfiramadhan.lr55@gmail.com",
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

}
