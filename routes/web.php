<?php

Route::get('{alias?}', \App\Http\Controllers\HomeController::class)->where('alias', '(.*)');