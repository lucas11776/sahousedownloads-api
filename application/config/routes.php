<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*

*---------------------------------------------------------------------------------------*
|       API END POINT          |        CONTROLLER            |         RIGHTS          |
*---------------------------------------------------------------------------------------*

*/
$route['default_controller']      = 'api/index';                // DEFUALT API ROUTE
$route['register']                = 'register/index';           // DONE (Auth)
$route['login']                   = 'login/index';              // DONE (Auth)
$route['songs']                   = 'songs/index';              // DONE
$route['song/latest']             = 'songs/index';              // DONE
$route['song/most/downloaded']    = 'songs/most_downloaded';    // DONE
$route['songs/edit/(:any)']       = 'songs/edit/$1';            // DONE (Auth)
$route['songs/delete']            = 'songs/delete';             // NOT DONE
$route['albums']                  = 'albums/index';             // DONE
$route['albums/most/downloaded']  = 'albums/update';            // DONE
$route['albums/search/(:any)']    = 'albums/search/$1';         // DONE
$route['albums/delete']           = 'albums/delete';            // NOT DONE 
$route['albums/(:any)']           = 'albums/single_album/$1';   // DONE
$route['upload/song']             = 'upload_song/index';        // DONE (Auth)
$route['upload/album']            = 'upload_album/index';       // DONE (Auth)
$route['upload/blog']             = 'upload_blog/index';        // DONE (Auth)
$route['newsletter/subscribe']    = 'newsletter/subscribe';     // NONE
$route['newsletter/unsubscribe']  = 'newsletter/unsubscribe';   // DONE
$route['password/recover']        = 'password/recover';         // NOT DONE
$route['password/reset']          = 'password/reset';           // NOT DONE
$route['404_override']            = 'api/page_not_found';       // INVALID REQUEST
$route['translate_uri_dashes']    = FALSE;