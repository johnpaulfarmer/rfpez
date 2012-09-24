<?php

class Contracts_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->only(array('new', 'create', 'edit', 'update'));
    $this->filter('before', 'correct_officer')->only(array('edit', 'update'));
    $this->filter('before', 'contract_exists')->only(array('show'));
  }

  public function action_index() {
    $view = View::make('contracts.index');
    $view->contracts = Contract::all();
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('contracts.show');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_new() {
    $view = View::make('contracts.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $solnbr = Input::get('solnbr');

    if (!preg_match('/^[0-9A-Za-z\-\_\s]+$/', $solnbr)) {
      Session::flash('errors', array('Invalid Sol Nbr.'));
      return $this->action_new();
    }

    $response = json_decode(file_get_contents('http://rfpez-apis.presidentialinnovationfellows.org/opportunities/fbozombie/'
                                              . $solnbr), true);

    if (!isset($response["solnbr"])) {
      Session::flash('errors', array("Couldn't find that contract on FBO."));
      return $this->action_new();
    } else if (Contract::where_fbo_solnbr($response["solnbr"])->first()) {
      Session::flash('errors', array("That contract already exists in the system."));
      return $this->action_new();
    } else if ((trim(strtolower($response["email"]))) != trim(strtolower(Auth::user()->email))) {
      Session::flash('errors', array("Couldn't verify email address."));
      return $this->action_new();
    }

    if (!Auth::user()->officer->is_verified()) {
      Auth::user()->officer->verify_with_solnbr($response["solnbr"]);
    }

    $contract = new Contract();
    $contract->fbo_solnbr = $response["solnbr"];
    $contract->officer_id = Auth::user()->officer->id;
    $contract->agency = $response["agency"];
    $contract->office = $response["office"];
    $contract->statement_of_work = $response["statement_of_work"];
    $contract->set_aside = $response["set_aside"];
    $contract->classification_code = $response["classification_code"];
    // Todo: Update parser to parse only the longer naics code
    //       and also parse the contract title.

    $contract->save();

    return Redirect::to_route('edit_contract', array($contract->id));
  }

  public function action_edit() {
    $view = View::make('contracts.edit');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_update() {
    $contract = Config::get('contract');
    $contract->fill(Input::get('contract'));
    $contract->save();
    return Redirect::to_route('edit_contract', array($contract->id));
  }

}

Route::filter('contract_exists', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('correct_officer', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract || $contract->officer->id != Auth::user()->officer->id) return Redirect::to('/');
  Config::set('contract', $contract);
});
