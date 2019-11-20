<?

/*
 * class LoginController
 */


class AuthClientController extends Quantum\Controller
{

    function __construct()
    {
        Quantum\Session::start();
    }


    protected function __pre_dispatch()
    {
        $this->setAutoRender(false);
    }


    public function index()
    {
        $this->login();
    }

    /**
     * Public: index
    */
    public function login()
    {
        if (Auth::isUserSessionOpen())
            redirect_to('/?session_already_started=1');

        $client = AuthClient::find_by_uri($this->app_config->getUri());

        $this->externalAuthLoginCheck($client->getPublicRSAKey());

        $this->request->redirect($client->getAuthUrl());
    }

    public function logout()
    {
        Auth::logout("/");
    }


    private function externalAuthLoginCheck($auth_client_key)
    {
        if ($this->request->isMissingParam("auth_client_token"))
            return;

        $user = Auth::getUserFromExternalAuthToken($auth_client_key);

        if ($user != false)
        {
            if ($this->request->isMissingParam('redirect_uri'))
                $this->request->setRequestParam("redirect_uri", $this->request->genFullURLFromURI("?auth_strategy_engaged=1"));

            $this->initUserSession($user);
        }
        else
        {
            Quantum\ApiException::accessDenied();
        }
    }


    private function initUserSession($user)
    {
        Auth::initUserSession($user);

        if($this->request->hasParam('redirect_uri'))
        {
            $redirect_url = $this->request->getParam('redirect_uri');
            redirect_to($redirect_url);
        }

        redirect_to('/');
    }


    
}
