<?

/*
 * class TestsController
 */

class TestsController extends Quantum\Controller
{
    
    /**
     * Create a controller, no dependency injection has happened.
    */
	function __construct()
	{

	}

    /**
     * Called after dependency injection, all environment variables are ready.
    */
	protected function __post_construct()
	{
        if ($this->is_production && $this->request->isMissingParam("qdebug"))
            Quantum\ApiException::resourceNotFound();
	}

    /**
     * Called before calling the main controller action, all environment variables are ready.
    */
	protected function __pre_dispatch()
	{
        $this->setAutoRender(false);
	}

    /**
     * Called after calling the main controller action, all vars set by controller are ready.
    */
	protected function __post_dispatch()
	{

	}

	/**
     * Called after calling the main controller action, before calling Quantum\Output::render
    */
	protected function __pre_render()
	{

	}

	/**
     * Called after calling Quantum\Output::render
    */
	protected function __post_render()
	{

	}


	/**
     * Public: index
    */
    public function index()
    {
      
      
    }


    function auth_client()
    {
        $c = AuthClient::find_by_id(1);

        $data = "the quick brown fox";

        $cypher = Quantum\Crypto::encrypt($data, $c->getEncryptionKeyString());

        echo $cypher. "<br/>";

        $uncyphered = Quantum\Crypto::decrypt($cypher, $c->getEncryptionKeyString());

        echo $uncyphered. "<br/>";

    }

    function key()
    {
        $data = Quantum\Crypto::genKey();

        echo $data. "<br/>";

    }

    function password()
    {
        $p = $this->request->getParam("p");

        if (Quantum\PasswordPolicy::isValid($p))
        {
            echo "valid";
        }
        else
        {
            echo "invalid";
        }
    }


    function csrf()
    {
        $this->setAutoRender(true);
        $this->setTemplate("login");
    }

    function session()
    {
        echo Quantum\Session::get('csrf');
    }

    function uri()
    {
        $this->setAutoRender(false);
        echo $this->request->getUri();
    }

    function geoip()
    {
        $ip = $this->request->getIp();

        //echo Quantum\GeoIp::getCountry($ip);
        //echo Quantum\GeoIp::getCountry("8.8.8.8");
        //echo Quantum\GeoIp::getCountryCode("50.193.88.145");
        //echo Quantum\GeoIp::getCountryCode($this->request->getIp());

        //dd($this->request->isLocalhost());

        qm_profiler_start('GeoIp::getCountryCode');
        echo Quantum\GeoIp::getCountryCode("50.193.88.145");
        qm_profiler_stop('GeoIp::getCountryCode');

        qm_profiler_start('MaxmindGeoIp::getCountryCode');
        echo Quantum\MaxmindGeoIp::getCountryCode("50.193.88.145");
        qm_profiler_stop('MaxmindGeoIp::getCountryCode');
    }

    function hashes()
    {
        $number = rand()*100000000000000;
        $string = Quantum\CSRF::create();
        $algos = hash_algos();

        array_push($algos, "xxhash");

        for($i=0;$i<1;$i++)
        {
            foreach($algos as $algo)
            {
                qm_profiler_start($algo);

                if ($algo == "xxhash")
                {
                    xxhash($number); //Number
                    xxhash($string); //QString
                }
                else {
                    hash($algo, $number); //Number
                    hash($algo, $string); //QString
                }

                qm_profiler_stop($algo);
            }
        }
    }

    function maxmindgeoip()
    {
        Quantum\MaxmindGeoIp::downloadDb();
    }

    function sparx()
    {
        ExampleModule\ExampleModule::someCall();
    }

    function string()
    {
        $s = string('The quick brown fox');
        echo $s.'<br/>';;
        echo $s->crc32().'<br/>';
        echo $s->crc32b()->withHtmlLineBreak();
        echo $s->md5().'<br/>';
        echo $s->hash().'<br/>';
        echo $s->hashCode().'<br/>';
        echo $s->length().'<br/>';
        echo $s->width().'<br/>';

        $s2 = string("The quick brown fox");

        echo $s2->withHtmlLineBreak();
        echo $s2->slug();
        echo $s2->toTitleCase()->withHtmlLineBreak();



        pr($s == $s2);

        echo $s->append(" eats carrots")->withHtmlLineBreak();
        echo $s->prepend("quote: ")->withHtmlLineBreak();
        echo $s;

        $s3 = qs('The quick brown fox');

        $s4 = string('"word"');

        pr(Quantum\QString::random());

        pr(Quantum\QString::quickRandom());

        pr($s2->equalsIgnoreCase($s3));

        pr($s2->slowCompare($s));
        pr($s2->equals($s));

        pr($s2->getFirstCharacter());
        pr($s2->getLastCharacter());

        pr($s2->lines());

        pr($s2->isUpperCase());

        pr($s2->toLowerCase());

        pr($s2->toUpperCase());

        pr($s2->containsWholeWordIgnoreCase('FOX'));

        pr($s2->indexOfWholeWordIgnoreCase('FOX'));

        pr($s2->underscored());

        pr($s2->titleize());

        pr($s2->containsAnyOf(['z', 'm']));

        pr($s2->containsOnly(['<', '@']));
        pr($s2->indexOf("x"));

        pr($s2->indexOfIgnoreCase("Q"));

        pr($s2->lastIndexOf("2"));

        pr($s2->lastIndexOfIgnoreCase("O"));

        pr($s2->getLastCharacter());

        pr($s2->dropLastCharacters(4));

        pr($s2->dropFirstCharacters(4));

        pr($s2->getLastCharacters(3));

        pr($s2->getFirstCharacters(3));

        pr($s2->fromFirstOccurrenceOf("he", false, false));

        pr($s2->fromLastOccurrenceOf("k", false, false));

        pr($s2->upToFirstOccurrenceOf("o", false, true));

        pr($s2->upToLastOccurrenceOf("o", false, true));

        pr($s2->replaceSection(4, 5, "slow"));
        pr($s2->replace("quick", "clever"));
        pr($s2->replaceCharacters("aeiou", "4310U"));

        pr($s2->retainCharacters("aeiou"));
        pr($s2->removeCharacters("aeiou"));
        pr($s2->initialSectionContainingOnly("Theo"));
        pr($s2->initialSectionNotContaining("ryz"));

        pr($s2->replaceFirstOccurrenceOf("o", "0"));
        pr($s4->isQuotedString());
        pr($s4);
        pr($s4->unquoted());
        pr($s2->quoted());

        pr(Quantum\QString::repeatedString("xyz", 3));
        $s5 = string("44");

        pr($s5->paddedLeft("0", 10));
        pr($s5->paddedRight("0", 10));
        pr($s5->paddedBoth("0", 10));

        $s6 = string("423.23432423432");
        pr($s6->getDecimalValue());

        $s7 = string("@# € ñ \"&#1491\",\"�\"");
        pr($s7->toUTF8());
        pr($s7->toUTF16());
        pr($s7->toUTF32());
        pr($s2->toJson());
        pr($s2->toRot13());
        pr($s2->explode(" "));
        pr($s2->serialize());
        pr($s2->toAscii());
        pr($s2->truncate(3));
        pr($s2->safeTruncate(3));
        pr($s2->truncateWords(3));
        pr($s2->toCamelCase());
        pr($s2->toStudlyCase());
        pr($s2->toSnakeCase());
        pr($s2->bin2hex());
        pr($s2->getWordCount());

        $hex = $s2->getHexValue();
        pr(Quantum\QString::fromHexValue($hex));

        $s4 = string($s2);
        pr($s4);

        echo $s2->hexDump('<br/>');

        $s11 = qs(false);
        pr($s11->isEmpty());

        pr(QM::config()->isCurrentRoutePublic());


    }

    function date()
    {
        printf("Right now is %s", Quantum\Date::now()->toDateTimeString());
    }



    function cached()
    {

        $user = User::find(1);

        $var = 'x';
        $foo = '-1';

        $val = $user->getCached($var, $foo);

        var_dump($val);

        $foo = '0';

        $val = $user->getCached($var, $foo);

        var_dump($val);


        $user2 = User::find(11);

        $var = 'x';
        $foo = 'RR';

        $val = $user2->getCached($var, $foo);

        var_dump($val);

        $foo = 'ZZZ';

        $val = $user2->getCached($var, $foo);

        var_dump($val);
    }

    
        
        
     
    
}

?>