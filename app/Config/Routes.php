<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Admin\UsersController;
use App\Controllers\Admin\GovStructureController;
use App\Controllers\Admin\BranchesController;
use App\Controllers\Admin\NaspController;
use App\Controllers\Admin\CorporatePlanController;
use App\Controllers\Admin\MtdpPlanController;
use App\Controllers\Admin\MTDPSpecificAreasController;
use App\Controllers\Admin\MTDPInvestmentsController;
use App\Controllers\Admin\RegionsController;
use App\Controllers\Admin\CommoditiesController;
use App\Controllers\DashboardController;
use App\Controllers\DakoiiController;
use App\Controllers\Home;
use App\Controllers\DocumentsController;
use App\Controllers\MeetingController;
use App\Controllers\AgreementsController;
use App\Controllers\MTDReportsController;
use App\Controllers\NASPReportsController;
use App\Controllers\WorkplanReportsController;
use App\Controllers\ActivityMapsReportsController;
use App\Controllers\HRReportsController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);
$routes->match(['GET', 'POST'], 'login', [Home::class, 'login']);
$routes->post('login-process', [Home::class, 'loginProcess']);
$routes->get('logout', [Home::class, 'logout']);

// Forgot Password routes
$routes->get('forgot-password', [Home::class, 'forgotPassword']);
$routes->post('forgot-password', [Home::class, 'processForgotPassword']);

// Dakoii specific routes
$routes->get('dakoii', [Home::class, 'dakoii']);
$routes->post('dakoii/login', [Home::class, 'dakoiiLogin']);

// Routes requiring Dakoii user login
$routes->group('dakoii', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', [DakoiiController::class, 'dashboard']);
    $routes->get('logout', [DakoiiController::class, 'logout']);
    $routes->get('profile', [DakoiiController::class, 'profile']);

    // Dakoii Users Management
    $routes->get('users', [DakoiiController::class, 'userList']);
    $routes->get('users/create', [DakoiiController::class, 'createUser']);
    $routes->post('users/store', [DakoiiController::class, 'storeUser']);
    $routes->get('users/edit/(:num)', [DakoiiController::class, 'editUser/$1']);
    $routes->post('users/update/(:num)', [DakoiiController::class, 'updateUser/$1']);
    $routes->get('users/delete/(:num)', [DakoiiController::class, 'deleteUser/$1']);
    $routes->get('users/roles', [DakoiiController::class, 'userRoles']);

    // Dakoii Administrators Management
    $routes->get('administrators', [DakoiiController::class, 'administrators']);
    $routes->get('administrators/create', [DakoiiController::class, 'createAdministrator']);
    $routes->post('administrators/store', [DakoiiController::class, 'storeAdministrator']);
    $routes->get('administrators/edit/(:num)', [DakoiiController::class, 'editAdministrator/$1']);
    $routes->post('administrators/update/(:num)', [DakoiiController::class, 'updateAdministrator/$1']);
    $routes->get('administrators/delete/(:num)', [DakoiiController::class, 'deleteAdministrator/$1']);
});

// Routes requiring regular user login
$routes->group('dashboard', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', [DashboardController::class, 'index']);
    $routes->get('profile', [DashboardController::class, 'profile']);
    $routes->post('update-profile', [DashboardController::class, 'updateProfile']);
    $routes->post('update-profile-photo', [DashboardController::class, 'updateProfilePhoto']);
    $routes->post('update-password', [DashboardController::class, 'updatePassword']);
});

// Admin routes
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    // Users Management - RESTful Routes
    $routes->get('users', [UsersController::class, 'index']);                    // GET /admin/users - list users
    $routes->get('users/create', [UsersController::class, 'create']);            // GET /admin/users/create - show create form
    $routes->post('users', [UsersController::class, 'store']);                   // POST /admin/users - store new user
    $routes->get('users/(:num)', [UsersController::class, 'show/$1']);           // GET /admin/users/{id} - show user
    $routes->get('users/(:num)/edit', [UsersController::class, 'edit/$1']);      // GET /admin/users/{id}/edit - show edit form
    $routes->put('users/(:num)', [UsersController::class, 'update/$1']);         // PUT /admin/users/{id} - update user
    $routes->patch('users/(:num)', [UsersController::class, 'update/$1']);       // PATCH /admin/users/{id} - update user
    $routes->get('users/(:num)/toggle-status', [UsersController::class, 'toggleStatus/$1']);  // GET toggle status
    $routes->post('users/(:num)/toggle-status', [UsersController::class, 'toggleStatus/$1']); // POST toggle status

    // --- Government Structure RESTful Routes ---
    $routes->group('gov-structure', static function ($routes) {
        // Base route for gov-structure -> show provinces
        $routes->get('/', [GovStructureController::class, 'provinceIndex']);

        // CSV Import Routes - Must come before general routes to avoid conflicts
        // Province CSV Import
        $routes->get('provinces/csv-template', [GovStructureController::class, 'downloadProvinceTemplate']);
        $routes->post('provinces/csv-import', [GovStructureController::class, 'importProvinces']);

        // District CSV Import
        $routes->get('provinces/(:num)/districts/csv-template', [GovStructureController::class, 'downloadDistrictTemplate/$1']);
        $routes->post('provinces/(:num)/districts/csv-import', [GovStructureController::class, 'importDistricts/$1']);

        // LLG CSV Import
        $routes->get('districts/(:num)/llgs/csv-template', [GovStructureController::class, 'downloadLlgTemplate/$1']);
        $routes->post('districts/(:num)/llgs/csv-import', [GovStructureController::class, 'importLlgs/$1']);

        // Ward CSV Import
        $routes->get('llgs/(:num)/wards/csv-template', [GovStructureController::class, 'downloadWardTemplate/$1']);
        $routes->post('llgs/(:num)/wards/csv-import', [GovStructureController::class, 'importWards/$1']);

        // Provinces (Top Level)
        $routes->get('provinces', [GovStructureController::class, 'provinceIndex']);
        $routes->get('provinces/new', [GovStructureController::class, 'provinceNew']); // Form view (optional)
        $routes->post('provinces', [GovStructureController::class, 'provinceCreate']); // Create action
        $routes->get('provinces/(:num)/edit', [GovStructureController::class, 'provinceEdit']); // Edit form (optional, for AJAX data)
        $routes->put('provinces/(:num)', [GovStructureController::class, 'provinceUpdate']); // Update action (PUT for REST)
        $routes->post('provinces/(:num)', [GovStructureController::class, 'provinceUpdate']); // Update action (POST for forms)
        $routes->delete('provinces/(:num)', [GovStructureController::class, 'provinceDelete']); // Delete action

        // Districts (Nested under Provinces)
        $routes->get('provinces/(:num)/districts', [GovStructureController::class, 'districtIndex/$1']);
        $routes->get('provinces/(:num)/districts/new', [GovStructureController::class, 'districtNew/$1']); // Form view (optional)
        $routes->post('provinces/(:num)/districts', [GovStructureController::class, 'districtCreate/$1']); // Create action
        // Note: Edit/Update/Delete for Districts are typically accessed directly by district ID
        $routes->get('districts/(:num)/edit', [GovStructureController::class, 'districtEdit/$1']); // Edit form (optional, for AJAX data)
        $routes->put('districts/(:num)', [GovStructureController::class, 'districtUpdate/$1']); // Update action (PUT for REST)
        $routes->post('districts/(:num)', [GovStructureController::class, 'districtUpdate/$1']); // Update action (POST for forms)
        $routes->delete('districts/(:num)', [GovStructureController::class, 'districtDelete/$1']); // Delete action

        // LLGs (Nested under Districts)
        $routes->get('districts/(:num)/llgs', [GovStructureController::class, 'llgIndex/$1']);
        $routes->get('districts/(:num)/llgs/new', [GovStructureController::class, 'llgNew/$1']); // Form view (optional)
        $routes->post('districts/(:num)/llgs', [GovStructureController::class, 'llgCreate/$1']); // Create action
        // Note: Edit/Update/Delete for LLGs are typically accessed directly by LLG ID
        $routes->get('llgs/(:num)/edit', [GovStructureController::class, 'llgEdit/$1']); // Edit form (optional, for AJAX data)
        $routes->put('llgs/(:num)', [GovStructureController::class, 'llgUpdate/$1']); // Update action (PUT for REST)
        $routes->post('llgs/(:num)', [GovStructureController::class, 'llgUpdate/$1']); // Update action (POST for forms)
        $routes->delete('llgs/(:num)', [GovStructureController::class, 'llgDelete/$1']); // Delete action

        // Wards (Nested under LLGs)
        $routes->get('llgs/(:num)/wards', [GovStructureController::class, 'wardIndex/$1']);
        $routes->get('llgs/(:num)/wards/new', [GovStructureController::class, 'wardNew/$1']); // Form view (optional)
        $routes->post('llgs/(:num)/wards', [GovStructureController::class, 'wardCreate/$1']); // Create action
        // Note: Edit/Update/Delete for Wards are typically accessed directly by Ward ID
        $routes->get('wards/(:num)/edit', [GovStructureController::class, 'wardEdit/$1']); // Edit form (optional, for AJAX data)
        $routes->put('wards/(:num)', [GovStructureController::class, 'wardUpdate/$1']); // Update action (PUT for REST)
        $routes->post('wards/(:num)', [GovStructureController::class, 'wardUpdate/$1']); // Update action (POST for forms)
        $routes->delete('wards/(:num)', [GovStructureController::class, 'wardDelete/$1']); // Delete action
    });
    // --- End Government Structure Routes ---

    // Branch Management - RESTful Routes
    $routes->get('branches', [BranchesController::class, 'index']);
    $routes->get('branches/new', [BranchesController::class, 'new']);
    $routes->post('branches', [BranchesController::class, 'create']);
    $routes->get('branches/(:num)', [BranchesController::class, 'show/$1']);
    $routes->get('branches/(:num)/edit', [BranchesController::class, 'edit/$1']);
    $routes->put('branches/(:num)', [BranchesController::class, 'update/$1']);
    $routes->post('branches/(:num)', [BranchesController::class, 'update/$1']); // Alternative for form submissions
    $routes->delete('branches/(:num)', [BranchesController::class, 'delete/$1']);
    $routes->post('branches/(:num)/toggle-status', [BranchesController::class, 'toggleStatus/$1']);
    $routes->get('branches/options', [BranchesController::class, 'getOptions']);

    // Regions Management - RESTful Routes
    $routes->get('regions', [RegionsController::class, 'index']);
    $routes->get('regions/new', [RegionsController::class, 'new']);
    $routes->post('regions', [RegionsController::class, 'create']);
    $routes->get('regions/(:num)', [RegionsController::class, 'show/$1']);
    $routes->get('regions/(:num)/edit', [RegionsController::class, 'edit/$1']);
    $routes->post('regions/(:num)', [RegionsController::class, 'update/$1']);
    $routes->get('regions/(:num)/delete', [RegionsController::class, 'delete/$1']);
    $routes->get('regions/(:num)/import-provinces', [RegionsController::class, 'importProvinces/$1']);
    $routes->post('regions/(:num)/import-provinces', [RegionsController::class, 'saveImportProvinces/$1']);
    $routes->get('regions/(:num)/remove-province/(:num)', [RegionsController::class, 'removeProvince/$1/$2']);

    // Commodities Management - RESTful Routes
    $routes->get('commodities', [CommoditiesController::class, 'index']);
    $routes->get('commodities/debug', [CommoditiesController::class, 'debug']); // Temporary debug route
    $routes->get('commodities/new', [CommoditiesController::class, 'new']);
    $routes->post('commodities', [CommoditiesController::class, 'create']);
    $routes->get('commodities/(:num)', [CommoditiesController::class, 'show/$1']);
    $routes->get('commodities/(:num)/edit', [CommoditiesController::class, 'edit/$1']);
    $routes->post('commodities/(:num)', [CommoditiesController::class, 'update/$1']);
    $routes->get('commodities/(:num)/delete', [CommoditiesController::class, 'delete/$1']);

    // NASP Plans Management Routes - RESTful
    $routes->group('nasp-plans', function($routes) {
        // Main NASP Plans - RESTful
        $routes->get('/', [NaspController::class, 'index']);
        $routes->get('new', [NaspController::class, 'new']);
        $routes->post('/', [NaspController::class, 'create']);
        $routes->get('(:num)', [NaspController::class, 'show/$1']);
        $routes->get('(:num)/edit', [NaspController::class, 'edit/$1']);
        $routes->post('(:num)', [NaspController::class, 'update/$1']);
        $routes->get('(:num)/toggle-status', [NaspController::class, 'showToggleStatus/$1']);
        $routes->post('(:num)/toggle-status', [NaspController::class, 'toggleStatus/$1']);
        $routes->post('(:num)/delete', [NaspController::class, 'delete/$1']);

        // APAs - RESTful
        $routes->get('(:num)/apas', [NaspController::class, 'apas/$1']);
        $routes->get('(:num)/apas/new', [NaspController::class, 'newApa/$1']);
        $routes->post('(:num)/apas', [NaspController::class, 'createApa/$1']);
        $routes->get('apas/(:num)', [NaspController::class, 'showApa/$1']);
        $routes->get('apas/(:num)/edit', [NaspController::class, 'editApa/$1']);
        $routes->post('apas/(:num)', [NaspController::class, 'updateApa/$1']);
        $routes->get('apas/(:num)/toggle-status', [NaspController::class, 'showToggleApaStatus/$1']);
        $routes->post('apas/(:num)/toggle-status', [NaspController::class, 'toggleApaStatus/$1']);

        // DIPs - RESTful (nested under APAs)
        $routes->get('apas/(:num)/dips', [NaspController::class, 'dips/$1']);
        $routes->get('apas/(:num)/dips/new', [NaspController::class, 'newDip/$1']);
        $routes->post('apas/(:num)/dips', [NaspController::class, 'createDip/$1']);
        $routes->get('apas/(:num)/dips/(:num)', [NaspController::class, 'showDip/$2']);
        $routes->get('apas/(:num)/dips/(:num)/edit', [NaspController::class, 'editDip/$2']);
        $routes->post('apas/(:num)/dips/(:num)', [NaspController::class, 'updateDip/$2']);
        $routes->get('apas/(:num)/dips/(:num)/toggle-status', [NaspController::class, 'showToggleDipStatus/$2']);
        $routes->post('apas/(:num)/dips/(:num)/toggle-status', [NaspController::class, 'toggleDipStatus/$2']);

        // Specific Areas - RESTful (nested under DIPs)
        $routes->get('apas/(:num)/dips/(:num)/specific-areas', [NaspController::class, 'specificAreas/$2']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/new', [NaspController::class, 'newSpecificArea/$2']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas', [NaspController::class, 'createSpecificArea/$2']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)', [NaspController::class, 'showSpecificArea/$3']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/edit', [NaspController::class, 'editSpecificArea/$3']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)', [NaspController::class, 'updateSpecificArea/$3']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/toggle-status', [NaspController::class, 'showToggleSpecificAreaStatus/$3']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/toggle-status', [NaspController::class, 'toggleSpecificAreaStatus/$3']);

        // Objectives - RESTful (nested under Specific Areas)
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives', [NaspController::class, 'objectives/$3']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/new', [NaspController::class, 'newObjective/$3']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives', [NaspController::class, 'createObjective/$3']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)', [NaspController::class, 'showObjective/$4']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/edit', [NaspController::class, 'editObjective/$4']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)', [NaspController::class, 'updateObjective/$4']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/toggle-status', [NaspController::class, 'showToggleObjectiveStatus/$4']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/toggle-status', [NaspController::class, 'toggleObjectiveStatus/$4']);

        // Outputs - RESTful (nested under Objectives)
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs', [NaspController::class, 'outputs/$4']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/new', [NaspController::class, 'newOutput/$4']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs', [NaspController::class, 'createOutput/$4']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)', [NaspController::class, 'showOutput/$5']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/edit', [NaspController::class, 'editOutput/$5']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)', [NaspController::class, 'updateOutput/$5']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/toggle-status', [NaspController::class, 'showToggleOutputStatus/$5']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/toggle-status', [NaspController::class, 'toggleOutputStatus/$5']);

        // Indicators - RESTful (nested under Outputs)
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators', [NaspController::class, 'indicators/$5']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/new', [NaspController::class, 'newIndicator/$5']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators', [NaspController::class, 'createIndicator/$5']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/(:num)', [NaspController::class, 'showIndicator/$6']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/(:num)/edit', [NaspController::class, 'editIndicator/$6']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/(:num)', [NaspController::class, 'updateIndicator/$6']);
        $routes->get('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/(:num)/toggle-status', [NaspController::class, 'showToggleIndicatorStatus/$6']);
        $routes->post('apas/(:num)/dips/(:num)/specific-areas/(:num)/objectives/(:num)/outputs/(:num)/indicators/(:num)/toggle-status', [NaspController::class, 'toggleIndicatorStatus/$6']);
    });

    // Corporate Plans Routes - RESTful
    $routes->group('corporate-plans', function ($routes) {
        $routes->get('/', [CorporatePlanController::class, 'index']);
        $routes->get('new', [CorporatePlanController::class, 'new']);
        $routes->post('/', [CorporatePlanController::class, 'create']);
        $routes->get('(:num)', [CorporatePlanController::class, 'show/$1']);
        $routes->get('(:num)/edit', [CorporatePlanController::class, 'edit/$1']);
        $routes->put('(:num)', [CorporatePlanController::class, 'update/$1']);
        $routes->post('(:num)', [CorporatePlanController::class, 'update/$1']); // For form compatibility
        $routes->delete('(:num)', [CorporatePlanController::class, 'delete/$1']);
        $routes->post('(:num)/delete', [CorporatePlanController::class, 'delete/$1']); // Add this for form-based deletion
        $routes->post('(:num)/toggle-status', [CorporatePlanController::class, 'toggleStatus/$1']);

        // Overarching Objectives
        $routes->get('overarching-objectives/(:num)', [CorporatePlanController::class, 'overarchingObjectives/$1']);
        $routes->post('overarching-objectives', [CorporatePlanController::class, 'createOverarchingObjective']);
        $routes->put('overarching-objectives/(:num)', [CorporatePlanController::class, 'updateOverarchingObjective/$1']);
        $routes->post('overarching-objectives/(:num)', [CorporatePlanController::class, 'updateOverarchingObjective/$1']);
        $routes->post('overarching-objectives/(:num)/toggle-status', [CorporatePlanController::class, 'toggleOverarchingObjectiveStatus/$1']);
        $routes->get('overarching-objectives/(:num)/edit', [CorporatePlanController::class, 'editOverarchingObjective/$1']);

        // Objectives
        $routes->get('objectives/(:num)', [CorporatePlanController::class, 'objectives/$1']);
        $routes->post('objectives', [CorporatePlanController::class, 'createObjective']);
        $routes->put('objectives/(:num)', [CorporatePlanController::class, 'updateObjective/$1']);
        $routes->post('objectives/(:num)', [CorporatePlanController::class, 'updateObjective/$1']);
        $routes->post('objectives/(:num)/toggle-status', [CorporatePlanController::class, 'toggleObjectiveStatus/$1']);
        $routes->get('objectives/(:num)/edit', [CorporatePlanController::class, 'editObjective/$1']);

        // KRAs
        $routes->get('kras/(:num)', [CorporatePlanController::class, 'kras/$1']);
        $routes->post('kras', [CorporatePlanController::class, 'createKra']);
        $routes->put('kras/(:num)', [CorporatePlanController::class, 'updateKra/$1']);
        $routes->post('kras/(:num)', [CorporatePlanController::class, 'updateKra/$1']);
        $routes->post('kras/(:num)/toggle-status', [CorporatePlanController::class, 'toggleKraStatus/$1']);
        $routes->get('kras/(:num)/edit', [CorporatePlanController::class, 'editKra/$1']);

        // Strategies
        $routes->get('strategies/(:num)', [CorporatePlanController::class, 'strategies/$1']);
        $routes->post('strategies', [CorporatePlanController::class, 'createStrategy']);
        $routes->get('strategies/(:num)/edit', [CorporatePlanController::class, 'editStrategy/$1']);
        $routes->put('strategies/(:num)', [CorporatePlanController::class, 'updateStrategy/$1']);
        $routes->post('strategies/(:num)', [CorporatePlanController::class, 'updateStrategy/$1']);
        $routes->post('strategies/(:num)/toggle-status', [CorporatePlanController::class, 'toggleStrategyStatus/$1']);
    });

    // MTDP Plans Routes - RESTful
    $routes->group('mtdp-plans', function ($routes) {
        // MTDP Plans
        $routes->get('/', [MtdpPlanController::class, 'index']);
        $routes->get('new', [MtdpPlanController::class, 'new']);
        $routes->post('/', [MtdpPlanController::class, 'create']);
        $routes->get('(:num)', [MtdpPlanController::class, 'show/$1']);
        $routes->get('(:num)/edit', [MtdpPlanController::class, 'edit/$1']);
        $routes->post('(:num)', [MtdpPlanController::class, 'update/$1']);
        $routes->put('(:num)', [MtdpPlanController::class, 'update/$1']);
        $routes->delete('(:num)', [MtdpPlanController::class, 'delete/$1']);
        $routes->post('(:num)/toggle-status', [MtdpPlanController::class, 'toggleStatus/$1']);

        // SPAs (Strategic Priority Areas)
        $routes->get('(:num)/spas', [MtdpPlanController::class, 'spas/$1']);
        $routes->get('spas/(:num)', [MtdpPlanController::class, 'spas/$1']); // Fixed: Point to existing spas method
        $routes->get('(:num)/spas/new', [MtdpPlanController::class, 'newSpa/$1']);
        $routes->post('(:num)/spas', [MtdpPlanController::class, 'createSpa/$1']);
        $routes->get('spas/(:num)/edit', [MtdpPlanController::class, 'editSpa/$1']);
        $routes->post('spas/(:num)', [MtdpPlanController::class, 'updateSpa/$1']);
        $routes->put('spas/(:num)', [MtdpPlanController::class, 'updateSpa/$1']);
        $routes->delete('spas/(:num)', [MtdpPlanController::class, 'deleteSpa/$1']);
        $routes->post('spas/(:num)/toggle-status', [MtdpPlanController::class, 'toggleSpaStatus/$1']);

        // DIPs (Development Investment Plans)
        $routes->get('spas/(:num)/dips', [MtdpPlanController::class, 'dips/$1']);
        $routes->get('spas/(:num)/dips/new', [MtdpPlanController::class, 'newDip/$1']);
        $routes->post('spas/(:num)/dips', [MtdpPlanController::class, 'createDip/$1']);
        $routes->get('dips/(:num)', [MtdpPlanController::class, 'showDip/$1']);
        $routes->get('dips/(:num)/edit', [MtdpPlanController::class, 'editDip/$1']);
        $routes->post('dips/(:num)', [MtdpPlanController::class, 'updateDip/$1']);
        $routes->put('dips/(:num)', [MtdpPlanController::class, 'updateDip/$1']);
        $routes->delete('dips/(:num)', [MtdpPlanController::class, 'deleteDip/$1']);
        $routes->post('dips/(:num)/toggle-status', [MtdpPlanController::class, 'toggleDipStatus/$1']);

        // Nested routes for DIPs under SPAs
        $routes->get('spas/(:num)/dips/(:num)', [MtdpPlanController::class, 'showDip/$2']);
        $routes->get('spas/(:num)/dips/(:num)/edit', [MtdpPlanController::class, 'editDip/$2']);

        // Specific Areas Routes
        $routes->get('dips/(:num)/specific-areas', [MTDPSpecificAreasController::class, 'index/$1']);
        $routes->get('dips/(:num)/specific-areas/new', [MTDPSpecificAreasController::class, 'new/$1']);
        $routes->post('specific-areas', [MTDPSpecificAreasController::class, 'create']);
        $routes->get('dips/(:num)/specific-areas/(:num)', [MTDPSpecificAreasController::class, 'show/$2']);
        $routes->get('dips/(:num)/specific-areas/(:num)/edit', [MTDPSpecificAreasController::class, 'edit/$2']);
        $routes->post('specific-areas/(:num)', [MTDPSpecificAreasController::class, 'update/$1']);
        $routes->post('specific-areas/(:num)/toggle-status', [MTDPSpecificAreasController::class, 'toggleStatus/$1']);

        // Investments Routes
        $routes->get('dips/(:num)/specific-areas/(:num)/investments', [MTDPInvestmentsController::class, 'index/$1/$2']);
        $routes->get('dips/(:num)/specific-areas/(:num)/investments/new', [MTDPInvestmentsController::class, 'new/$1/$2']);
        $routes->post('investments', [MTDPInvestmentsController::class, 'create']);
        $routes->get('dips/(:num)/specific-areas/(:num)/investments/(:num)', [MTDPInvestmentsController::class, 'show/$3']);
        $routes->get('dips/(:num)/specific-areas/(:num)/investments/(:num)/edit', [MTDPInvestmentsController::class, 'edit/$3']);
        $routes->post('investments/(:num)', [MTDPInvestmentsController::class, 'update/$1']);
        $routes->post('investments/(:num)/toggle-status', [MTDPInvestmentsController::class, 'toggleStatus/$1']);

        // KRAs Routes
        $routes->get('investments/(:num)/kras', [\App\Controllers\Admin\MTDPKRAsController::class, 'index/$1']);
        $routes->get('investments/(:num)/kras/new', [\App\Controllers\Admin\MTDPKRAsController::class, 'new/$1']);
        $routes->post('kras', [\App\Controllers\Admin\MTDPKRAsController::class, 'create']);
        $routes->get('kras/(:num)', [\App\Controllers\Admin\MTDPKRAsController::class, 'show/$1']);
        $routes->get('kras/(:num)/edit', [\App\Controllers\Admin\MTDPKRAsController::class, 'edit/$1']);
        $routes->post('kras/(:num)', [\App\Controllers\Admin\MTDPKRAsController::class, 'update/$1']);
        $routes->post('kras/(:num)/toggle-status', [\App\Controllers\Admin\MTDPKRAsController::class, 'toggleStatus/$1']);

        // Strategies Routes
        $routes->get('kras/(:num)/strategies', [\App\Controllers\Admin\MTDPStrategiesController::class, 'index/$1']);
        $routes->get('kras/(:num)/strategies/new', [\App\Controllers\Admin\MTDPStrategiesController::class, 'new/$1']);
        $routes->post('strategies', [\App\Controllers\Admin\MTDPStrategiesController::class, 'create']);
        $routes->get('strategies/(:num)', [\App\Controllers\Admin\MTDPStrategiesController::class, 'show/$1']);
        $routes->get('strategies/(:num)/edit', [\App\Controllers\Admin\MTDPStrategiesController::class, 'edit/$1']);
        $routes->post('strategies/(:num)', [\App\Controllers\Admin\MTDPStrategiesController::class, 'update/$1']);
        $routes->post('strategies/(:num)/toggle-status', [\App\Controllers\Admin\MTDPStrategiesController::class, 'toggleStatus/$1']);

        // Indicators Routes
        $routes->get('strategies/(:num)/indicators', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'index/$1']);
        $routes->get('strategies/(:num)/indicators/new', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'new/$1']);
        $routes->post('indicators', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'create']);
        $routes->get('indicators/(:num)', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'show/$1']);
        $routes->get('indicators/(:num)/edit', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'edit/$1']);
        $routes->post('indicators/(:num)', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'update/$1']);
        $routes->post('indicators/(:num)/toggle-status', [\App\Controllers\Admin\MTDPIndicatorsController::class, 'toggleStatus/$1']);
    });

    // Legacy MTDP routes for backward compatibility
    $routes->post('mtdp-plans/create', [MtdpPlanController::class, 'create']);
    $routes->post('mtdp-plans/update', [MtdpPlanController::class, 'update']);
    $routes->post('mtdp-plans/toggle-status', [MtdpPlanController::class, 'toggleStatus']);
    $routes->get('mtdp-plans/details/(:num)', [MtdpPlanController::class, 'getPlanDetails/$1']);

    $routes->post('mtdp-plans/create-spa', [MtdpPlanController::class, 'createSpa']);
    $routes->post('mtdp-plans/update-spa', [MtdpPlanController::class, 'updateSpa']);
    $routes->post('mtdp-plans/toggle-spa-status', [MtdpPlanController::class, 'toggleSpaStatus']);
    $routes->get('mtdp-plans/spa-details/(:num)', [MtdpPlanController::class, 'getSpaDetails/$1']);

    $routes->get('mtdp-plans/create-dip-form/(:num)', [MtdpPlanController::class, 'newDip/$1']);
    $routes->get('mtdp-plans/edit-dip-form/(:num)', [MtdpPlanController::class, 'editDip/$1']);
    $routes->get('mtdp-plans/view-dip/(:num)', [MtdpPlanController::class, 'showDip/$1']);
    $routes->post('mtdp-plans/create-dip', [MtdpPlanController::class, 'createDip']);
    $routes->post('mtdp-plans/update-dip', [MtdpPlanController::class, 'updateDip']);
    $routes->post('mtdp-plans/update-dip/(:num)', [MtdpPlanController::class, 'updateDip/$1']);
    $routes->post('mtdp-plans/toggle-dip-status', [MtdpPlanController::class, 'toggleDipStatus']);
    $routes->get('mtdp-plans/dip-details/(:num)', [MtdpPlanController::class, 'getDipDetails/$1']);

    // Organization Settings Routes
    $routes->get('org-settings', 'Admin\SettingsController::index');
    $routes->get('org-settings/new', 'Admin\SettingsController::new');
    $routes->post('org-settings', 'Admin\SettingsController::create');
    $routes->get('org-settings/(:num)', 'Admin\SettingsController::show/$1');
    $routes->get('org-settings/(:num)/edit', 'Admin\SettingsController::edit/$1');
    $routes->post('org-settings/(:num)', 'Admin\SettingsController::update/$1');
    $routes->get('org-settings/(:num)/delete', 'Admin\SettingsController::delete/$1');
});

// SME Routes - RESTful
$routes->group('smes', static function($routes){
    $routes->get('/', [\App\Controllers\SmeController::class, 'index']);
    $routes->get('new', [\App\Controllers\SmeController::class, 'new']);
    $routes->post('/', [\App\Controllers\SmeController::class, 'create']);
    $routes->get('(:num)', [\App\Controllers\SmeController::class, 'show/$1']);
    $routes->get('(:num)/edit', [\App\Controllers\SmeController::class, 'edit/$1']);
    $routes->post('(:num)', [\App\Controllers\SmeController::class, 'update/$1']);
    $routes->get('(:num)/delete', [\App\Controllers\SmeController::class, 'delete/$1']);
    $routes->post('(:num)/toggle-status', [\App\Controllers\SmeController::class, 'toggleStatus/$1']);

    // Location AJAX routes
    $routes->get('districts/(:num)', [\App\Controllers\SmeController::class, 'getDistricts/$1']);
    $routes->get('llgs/(:num)', [\App\Controllers\SmeController::class, 'getLlgs/$1']);

    // Nested Staff Routes
    $routes->get('staff/(:num)', [\App\Controllers\SmeController::class, 'staff_index/$1']);
    $routes->get('staff/(:num)/new', [\App\Controllers\SmeController::class, 'staff_new/$1']);
    $routes->post('staff/(:num)', [\App\Controllers\SmeController::class, 'staff_create/$1']);
    $routes->get('staff/(:num)/(:num)/edit', [\App\Controllers\SmeController::class, 'staff_edit/$1/$2']);
    $routes->post('staff/(:num)/(:num)', [\App\Controllers\SmeController::class, 'staff_update/$1/$2']);
    $routes->get('staff/(:num)/(:num)/delete', [\App\Controllers\SmeController::class, 'staff_delete/$1/$2']);
});

// Commodity Production Routes - RESTful (for commodity role users)
$routes->group('commodity-boards', ['filter' => 'auth'], static function($routes){
    $routes->get('/', [\App\Controllers\CommodityProductionController::class, 'index']);
    $routes->get('new', [\App\Controllers\CommodityProductionController::class, 'new']);
    $routes->post('/', [\App\Controllers\CommodityProductionController::class, 'create']);
    $routes->get('(:num)', [\App\Controllers\CommodityProductionController::class, 'show/$1']);
    $routes->get('(:num)/edit', [\App\Controllers\CommodityProductionController::class, 'edit/$1']);
    $routes->post('(:num)', [\App\Controllers\CommodityProductionController::class, 'update/$1']);
    $routes->get('(:num)/delete', [\App\Controllers\CommodityProductionController::class, 'delete/$1']);
});

// Dakoii Organization User routes (RESTful)
$routes->get('/dakoii_org/user/(:num)', 'DakoiiController::dakoii_org_user_index/$1');
$routes->get('/dakoii_org/user/new/(:num)', 'DakoiiController::dakoii_org_user_new/$1');
$routes->post('/dakoii_org/user/create/(:num)', 'DakoiiController::dakoii_org_user_create/$1');
$routes->get('/dakoii_org/user/show/(:num)/(:num)', 'DakoiiController::dakoii_org_user_show/$1/$2');
$routes->get('/dakoii_org/user/edit/(:num)/(:num)', 'DakoiiController::dakoii_org_user_edit/$1/$2');
$routes->post('/dakoii_org/user/update/(:num)/(:num)', 'DakoiiController::dakoii_org_user_update/$1/$2');
$routes->get('/dakoii_org/user/delete/(:num)/(:num)', 'DakoiiController::dakoii_org_user_delete/$1/$2');

// Documents Management Routes
$routes->group('documents', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'DocumentsController::index');
    $routes->get('new', 'DocumentsController::new');
    $routes->post('create', 'DocumentsController::create');
    $routes->get('edit/(:num)', 'DocumentsController::edit/$1');
    $routes->post('update/(:num)', 'DocumentsController::update/$1');
    $routes->get('delete/(:num)', 'DocumentsController::delete/$1');
    $routes->get('file/new/(:num)', 'DocumentsController::newDocument/$1');
    $routes->post('file/create', 'DocumentsController::createDocument');
    $routes->get('file/view/(:num)', 'DocumentsController::viewDocument/$1');
    $routes->get('file/download/(:num)', 'DocumentsController::downloadDocument/$1');
    $routes->get('file/delete/(:num)', 'DocumentsController::deleteDocument/$1');
});

// Meeting routes (RESTful)
$routes->get('meetings', 'MeetingController::index');
$routes->get('meetings/new', 'MeetingController::new');
$routes->post('meetings', 'MeetingController::create');
$routes->get('meetings/(:num)', 'MeetingController::show/$1');
$routes->get('meetings/edit/(:num)', 'MeetingController::edit/$1');
$routes->post('meetings/update/(:num)', 'MeetingController::update/$1');
$routes->get('meetings/delete/(:num)', 'MeetingController::delete/$1');
$routes->get('meetings/download/(:num)/(:num)', 'MeetingController::download/$1/$2');
$routes->post('meetings/deleteAttachment/(:num)/(:num)', 'MeetingController::deleteAttachment/$1/$2');
$routes->post('meetings/status/(:num)', 'MeetingController::updateStatus/$1');

// Agreements routes (RESTful)
$routes->group('agreements', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'AgreementsController::index');
    $routes->get('new', 'AgreementsController::new');
    $routes->post('create', 'AgreementsController::create'); // Using post for form submission
    $routes->get('(:num)', 'AgreementsController::show/$1');
    $routes->get('edit/(:num)', 'AgreementsController::edit/$1');
    $routes->post('update/(:num)', 'AgreementsController::update/$1'); // Using post for form submission
    $routes->delete('delete/(:num)', 'AgreementsController::delete/$1'); // Using delete method (requires _method field in form)
    $routes->post('delete/(:num)', 'AgreementsController::delete/$1'); // Fallback for forms without method spoofing

    // Attachment routes
    $routes->get('download/(:num)/(:num)', 'AgreementsController::downloadAttachment/$1/$2');
    $routes->post('delete_attachment/(:num)/(:num)', 'AgreementsController::deleteAttachment/$1/$2'); // Route to handle attachment deletion
});

// Workplan routes (RESTful) - Basic CRUD
$routes->get('workplans', 'WorkplanController::index');
$routes->get('workplans/new', 'WorkplanController::new');
$routes->post('workplans/create', 'WorkplanController::create');
$routes->get('workplans/(:num)', 'WorkplanController::show/$1');
$routes->get('workplans/edit/(:num)', 'WorkplanController::edit/$1');
$routes->post('workplans/update/(:num)', 'WorkplanController::update/$1');
$routes->put('workplans/update/(:num)', 'WorkplanController::update/$1'); // Add PUT method support
$routes->get('workplans/delete/(:num)', 'WorkplanController::delete/$1');

// Workplan Activities routes (RESTful)
$routes->group('workplans/(:num)/activities', function($routes) {
    $routes->get('/', 'WorkplanActivitiesController::index/$1');
    $routes->get('new', 'WorkplanActivitiesController::new/$1');
    $routes->post('create', 'WorkplanActivitiesController::create/$1');
    $routes->get('(:num)', 'WorkplanActivitiesController::show/$1/$2');
    $routes->get('(:num)/edit', 'WorkplanActivitiesController::edit/$1/$2');
    $routes->post('(:num)/update', 'WorkplanActivitiesController::update/$1/$2');
    $routes->get('(:num)/delete', 'WorkplanActivitiesController::delete/$1/$2');

    // NASP Plan linking routes
    $routes->get('(:num)/plans', 'WorkplanController::activityPlans/$1/$2');
    $routes->post('(:num)/plans/link', 'WorkplanController::linkActivityPlan/$1/$2');
    $routes->match(['GET', 'POST'], '(:num)/plans/(:num)/delete', 'WorkplanController::deleteActivityPlan/$1/$2/$3');

    // Others links routes
    $routes->get('(:num)/others', 'WorkplanOthersController::index/$1/$2');
    $routes->get('(:num)/others/new', 'WorkplanOthersController::new/$1/$2');
    $routes->post('(:num)/others', 'WorkplanOthersController::create/$1/$2');
    $routes->get('(:num)/others/(:num)/edit', 'WorkplanOthersController::edit/$1/$2/$3');
    $routes->post('(:num)/others/(:num)', 'WorkplanOthersController::update/$1/$2/$3');
    $routes->post('(:num)/others/(:num)/delete', 'WorkplanOthersController::delete/$1/$2/$3');
});

// Route for getting districts by province ID (AJAX)
$routes->get('workplans/get-districts/(:num)', 'WorkplanActivitiesController::getDistricts/$1');

// Activities routes (RESTful)
$routes->group('activities', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ActivitiesController::index');
    $routes->get('(:num)', 'ActivitiesController::show/$1');
    $routes->get('(:num)/implement', 'ActivitiesController::implement/$1');
    $routes->post('(:num)/save-implementation', 'ActivitiesController::saveImplementation/$1');
    $routes->post('(:num)/submit-for-supervision', 'ActivitiesController::submitForSupervision/$1');
    $routes->get('get-districts/(:num)', 'ActivitiesController::getDistricts/$1');
});

// Output Activities routes (RESTful)
$routes->group('output-activities', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'WorkplanOutputActivitiesController::index');                    // GET /output-activities
    $routes->get('new', 'WorkplanOutputActivitiesController::new');                   // GET /output-activities/new
    $routes->post('create', 'WorkplanOutputActivitiesController::create');            // POST /output-activities/create
    $routes->get('(:num)', 'WorkplanOutputActivitiesController::show/$1');            // GET /output-activities/{id}
    $routes->get('(:num)/edit', 'WorkplanOutputActivitiesController::edit/$1');       // GET /output-activities/{id}/edit
    $routes->post('(:num)/update', 'WorkplanOutputActivitiesController::update/$1');  // POST /output-activities/{id}/update
    $routes->post('(:num)/delete', 'WorkplanOutputActivitiesController::delete/$1');  // POST /output-activities/{id}/delete

    // AJAX endpoints
    $routes->get('by-workplan/(:num)', 'WorkplanOutputActivitiesController::getByWorkplan/$1');  // GET /output-activities/by-workplan/{workplanId}
    $routes->get('by-proposal/(:num)', 'WorkplanOutputActivitiesController::getByProposal/$1');  // GET /output-activities/by-proposal/{proposalId}
});

// AJAX API routes for plan data
$routes->group('api', function($routes) {
    $routes->get('mtdp/plans', 'AjaxController::getMtdpPlans');
    $routes->get('mtdp/spas/(:num)', 'AjaxController::getMtdpSpas/$1');
    $routes->get('mtdp/dips/(:num)', 'AjaxController::getMtdpDips/$1');
    $routes->get('mtdp/specific-areas/(:num)', 'AjaxController::getMtdpSpecificAreas/$1');
    $routes->get('mtdp/investments/(:num)', 'AjaxController::getMtdpInvestments/$1');
    $routes->get('mtdp/kras/(:num)', 'AjaxController::getMtdpKras/$1');
    $routes->get('mtdp/strategies/(:num)', 'AjaxController::getMtdpStrategies/$1');
    $routes->get('mtdp/indicators/(:num)', 'AjaxController::getMtdpIndicators/$1');
    $routes->get('mtdp/all-strategies', 'AjaxController::getAllMtdpStrategies');
    $routes->get('mtdp/strategy-hierarchy/(:num)', 'AjaxController::getMtdpStrategyHierarchy/$1');
    $routes->get('nasp/apas/(:num)', 'AjaxController::getNaspApas/$1');
    $routes->get('nasp/dips/(:num)', 'AjaxController::getNaspDips/$1');
    $routes->get('nasp/specific-areas/(:num)', 'AjaxController::getNaspSpecificAreas/$1');
    $routes->get('nasp/objectives/(:num)', 'AjaxController::getNaspObjectives/$1');
    $routes->get('nasp/outputs/(:num)', 'AjaxController::getNaspOutputs/$1');
    $routes->get('nasp/all-outputs', 'AjaxController::getAllNaspOutputs');
    $routes->get('nasp/output-hierarchy/(:num)', 'AjaxController::getNaspOutputHierarchy/$1');
    $routes->get('corporate/overarching/(:num)', 'AjaxController::getCorporateOverarching/$1');
    $routes->get('corporate/objectives/(:num)', 'AjaxController::getCorporateObjectives/$1');
    $routes->get('corporate/kras/(:num)', 'AjaxController::getCorporateKras/$1');
    $routes->get('corporate/strategies/(:num)', 'AjaxController::getCorporateStrategies/$1');
    $routes->get('corporate/all-strategies', 'AjaxController::getAllCorporateStrategies');
    $routes->get('corporate/strategy-hierarchy/(:num)', 'AjaxController::getCorporateStrategyHierarchy/$1');
});

// Dakoii Portal routes (RESTful)
$routes->get('/dakoii_dashboard', 'DakoiiController::index');

// Proposals routes (RESTful)
$routes->group('proposals', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProposalsController::index');
    $routes->get('new', 'ProposalsController::new');
    $routes->post('create', 'ProposalsController::create');
    $routes->get('(:num)', 'ProposalsController::show/$1');
    $routes->get('edit/(:num)', 'ProposalsController::edit/$1');
    $routes->post('update/(:num)', 'ProposalsController::update/$1');
    $routes->get('status/(:num)', 'ProposalsController::status/$1');
    $routes->post('status/(:num)', 'ProposalsController::updateStatus/$1');
    $routes->get('supervise/(:num)', 'ProposalsController::supervise/$1');
    $routes->post('resend/(:num)', 'ProposalsController::resendProposal/$1');
    $routes->post('approve/(:num)', 'ProposalsController::approveProposal/$1');
    $routes->get('rate/(:num)', 'ProposalsController::rate/$1');
    $routes->post('rate/(:num)', 'ProposalsController::submitRating/$1');
    $routes->get('get-activities', 'ProposalsController::getActivities');
    $routes->get('get-districts', 'ProposalsController::getDistricts');
});

// MTDP Reports routes
$routes->get('reports/mtdp', [MTDReportsController::class, 'index']);

// NASP Reports routes
$routes->get('reports/nasp', [NASPReportsController::class, 'index']);
$routes->get('reports/nasp/filter-data', [NASPReportsController::class, 'getFilterData']);
$routes->get('reports/nasp/filtered-data', [NASPReportsController::class, 'getFilteredData']);

// Workplan Reports routes
$routes->get('reports/workplan', 'WorkplanReportsController::index');

// Activities Map Reports routes
$routes->get('reports/activities-map', 'ActivityMapsReportsController::index');

// Commodity Reports routes
$routes->get('reports/commodity', 'CommodityReportsController::index');

// HR Reports routes
$routes->get('reports/hr', 'HRReportsController::index');

// Test JavaScript PDF generation
$routes->get('test-pdf-js', 'TestPdfJsController::index');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
// Environment based routes
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
