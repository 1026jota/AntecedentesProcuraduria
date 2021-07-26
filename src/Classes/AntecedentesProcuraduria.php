<?php

namespace Jota\AntecedentesProcuraduria\Classes;

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class AntecedentesProcuraduria
{
    /**
     * contien el buscador para realizar la busqueda
     * @var mixed
     */
    private $browser;

    /**
     * contiene la instancia de la clase puppeteer
     * @var Puppeteer
     */
    private Puppeteer $puppeteer;

    /**
     * contiene el url del iframe donde se encuentra
     * el formulario
     * @var string
     */
    private string $url_iframe;

    /**
     * Resultado de la busqueda
     * @var array
     */
    private array $result;


    public function __construct()
    {
        $this->puppeteer = new Puppeteer([
            'executable_path' => '/home/developer/.nvm/versions/node/v12.16.3/bin/node',
        ]);
        $this->browser = $this->puppeteer->launch(['headless' => true]);
        $this->url_iframe = $this->getUrlIframe();
        $this->result['is_registered'] = true;
        $this->result['result'] = [];
    }

    /**
     * Obtiene el url para llamar el form
     * @author alexander montaño
     * @return string
     */
    private function getUrlIframe(): string
    {
        $page = $this->browser->newPage();
        $page->goto(config('antecedentesprocuraduria.url'));
        $form_url = $page->evaluate(JsFunction::createWithBody("
            return document.querySelector('iframe').src
        "));
        unset($page);
        return $form_url;
    }

    /**
     * Obtiene un arreglo con las posibles preguntas
     * a responder para realizar la busqueda
     * @author alexander montaño
     * @return array
     */
    private function getQuestions(): array
    {
        $questions = config('antecedentesprocuraduria.questions');
        return json_decode($questions, true);
    }

    /**
     * Limpia la respuesta obtenida por la procuraduria y se 
     * guarda en result[]
     * @author alexander montaño
     * @param string $response : respuesta procuraduria
     * @return void
     */
    private function setResult(string $response): void
    {
        $array_response = explode('<h2>', $response);
        if (count($array_response) === 1) {
            $this->result['is_registered'] = false;
            $this->result['result'] = [
                'name' => '',
                'response' => 'El número de cedula no genera resultados',
            ];
        } else {
            $response = str_replace('  ', '', str_replace('</h2>', '', $array_response[1]));
            $response = str_replace(PHP_EOL, '', $response);
            $this->result['is_registered'] = true;
            $this->result['result'] = [
                'name' => $this->getName($response),
                'response' => $response
            ];
        }
    }

    /**
     * Limpia la respuesta obtenida por la procuraduria y obtiene
     * el nombre asociado a esa cedula
     * @author alexander montaño
     * @param string $response : respuesta procuraduria
     * @return string
     */
    private function getName(string $response): string
    {
        $array = explode('<span>', $response);
        $clean_lastname = explode('</span>', $array[4]);
        $name = $array[1] . ' ' . $array[2] . ' ' . $array[3] . ' ' . $clean_lastname[0];
        $name = str_replace('</span>', '', $name);
        return $name;
    }

    /**
     * retorna el array con la repuesta
     * obtenida
     * @author alexander montaño
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * Busca en la pagina de la procuraduria un numero de cedula(Colombiana)
     * y retorna uan respuesta con nombre y antecedentes penales
     * @author alexander montaño
     * @param string $numero_cedula : identificacion a buscar
     * @return void
     */
    public function searchByCedula(string $numero_cedula): void
    {
        $page = $this->browser->newPage();
        $page->goto($this->url_iframe);
        $pregunta = $page->evaluate(JsFunction::createWithBody("
            return document.getElementById('lblPregunta').innerHTML
        "));

        foreach ($this->getQuestions() as $question) {
            if ($question['question'] === $pregunta)
                $response = $question['response'];
        }

        if (!isset($response)) {
            $page->close();
            $this->searchByCedula($numero_cedula);
        }

        $pregunta = $page->evaluate(JsFunction::createWithBody("
            return document.getElementById('ddlTipoID').value = 1
        "));

        $page->type('#txtNumID', $numero_cedula);
        $page->type('#txtRespuestaPregunta', $response);
        $page->click('#btnConsultar');
        $page->waitFor(3000);
        $result = $page->evaluate(JsFunction::createWithBody("
            return document.getElementById('divSec').innerHTML
        ")->async(true));

        $this->browser->close();
        $this->setResult($result);
    }
}
