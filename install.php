<?php
/**
 * FluxBB - fast, light, user-friendly PHP forum software
 * Copyright (C) 2008-2012 FluxBB.org
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public license for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category	FluxBB
 * @package		Core
 * @copyright	Copyright (c) 2008-2012 FluxBB (http://fluxbb.org)
 * @license		http://www.gnu.org/licenses/gpl.html	GNU General Public License
 */

require 'vendor/autoload.php';

use FluxBB\Installer\Application;
use FluxBB\Installer\InstallerDatabaseService;
use FluxBB\Installer\InstallerSessionService;
use FluxBB\Installer\InstallerValidationService;
use FluxBB\Services\ViewService;
use Illuminate\CookieServiceProvider;
use Illuminate\Encrypter;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

$installer = new Application;

set_fluxbb($installer);

$installer['path'] = __DIR__.'/';
$installer['path.config'] = __DIR__.'/config/';
$installer['path.view'] = __DIR__.'/views/';
$installer['path.cache'] = __DIR__.'/cache/';
$installer['files'] = new Filesystem;
$installer['encrypter'] = new Encrypter('fluxbb_install'); // TODO: Do we need a real secret key for installation?
$installer['events'] = new Dispatcher;

$installer['config'] = array(
	'app.locale'			=> 'en',	// TODO: Set language!
	'app.fallback_locale'	=> 'en',
);

$installer->register(new CookieServiceProvider($installer));
$installer->register(new HashServiceProvider($installer));
$installer->register(new InstallerDatabaseService($installer));
$installer->register(new InstallerSessionService($installer));
$installer->register(new TranslationServiceProvider($installer));
$installer->register(new InstallerValidationService($installer));
$installer->register(new ViewService($installer));

Illuminate\Support\Facade::setFacadeApplication($installer);

$installer->run();
