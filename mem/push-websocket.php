<?php
/**
 * A daemon of PHP Push WebSocket
 * @author  Sann-Remy Chea <http://srchea.com>
 * @license This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @version 1.0.0
 */

error_reporting(E_ALL);

if (php_sapi_name() === 'cli' or defined('STDIN')) {
    
    require_once __DIR__.'/../httpdocs/src/includes/autoload.php'; // Autoload files using Composer autoload
    
    set_time_limit(0);
    
    // variables
    $address = '192.10.0.181';
    $port = 18000;
    $verboseMode = true;
    $GLOBALS['notification_folder'] = '/var/www/vhosts/master-ds/httpdocs/cache/qf_notifications/';
    
    $server = new \PushWebSocket\Server($address, $port, $verboseMode);
    $server->run();
}
